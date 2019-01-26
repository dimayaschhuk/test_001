<?php

namespace App\Http\Controllers;

use App\BaseModels\Culture;
use App\BaseModels\Problem;
use App\BaseModels\ProblemGroup;
use App\Commands\SendMessageCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Telegram;


class TelegramController extends Controller
{
    const TIME_CACHE = 1;

    public function webhook()
    {
        $telegramUser = \Telegram::getWebhookUpdates()['message'];
        $chatId = $telegramUser['from']['id'];
        $text = $telegramUser['text'];
//        $this->test($chatId, $chatId);
        if (Cache::has($telegramUser['from']['id'])) {
            $data = Cache::get($telegramUser['from']['id']);
            if ($data['flow'] == 'testFlow') {
                $method = next_method($data);
//                $this->test($chatId,'next method: '.$method);

                if ($method == 'sendTextCulture') {
                    $this->sendTextCulture($chatId, $text);
                }
                if ($method == 'searchCulture') {
                    $this->searchCulture($chatId, $text);
                }
                if ($method == 'selectCulture') {
                    $this->selectCulture($chatId, $text);
                }


                if ($method == 'sendTextProblemGroup') {
                    $this->sendTextProblemGroup($chatId, $text);
                }
                if ($method == 'selectProblemGroup') {
                    $this->selectProblemGroup($chatId, $text);
                }

                if ($method == 'sendTextProblem') {
                    $this->sendTextProblem($chatId, $text);
                }

            } else {
                $this->test($chatId, 'not testFlow');
            }
        } else {
            $value = ['flow' => 'testFlow', 'method' => 'welcome'];
            Cache::put($telegramUser['from']['id'], $value, 1);
            $response = \Telegram::sendMessage([
                'chat_id' => $telegramUser['from']['id'],
                'text'    => 'Добро рожаловть',
            ]);
            $response->getMessageId();
            $keyboard = [
                ['Продукты', 'Защита культур'],
            ];
            $reply_markup = \Telegram::replyKeyboardHide([
                'keyboard'          => $keyboard,
                'resize_keyboard'   => TRUE,
                'one_time_keyboard' => TRUE,
            ]);
            $response = \Telegram::sendMessage([
                'chat_id'      => $chatId,
                'text'         => 'Виберіть гілку',
                'reply_markup' => $reply_markup,
            ]);
            $response->getMessageId();
        }


    }


    //отправляєм 'Введіть назву культури або перші букви'
    public function sendTextCulture($chatId, $text)
    {
        send_text($chatId, 'Введіть назву культури або перші букви');

        $value = ['flow' => 'testFlow', 'method' => 'sendTextCulture'];
        Cache::put($chatId, $value, self::TIME_CACHE);
    }

    //если находим культуру chooseGroup(вибор групи проблем)
    //если находим несколько культур придлагаем вибрать придлагаем вибрать из них одну
    //если не находим не одной отправляем на sendTextEnterNameCulture('Введіть назву культури або перші букви')
    public function searchCulture($chatId, $text)
    {

        if (Culture::where('name', $text)->count() === 1) {
            $this->selectCulture($chatId, $text);
            exit;
        }

        if (Culture::where('name', 'LIKE', "%{$text}%")->count() === 0) {
            $this->sendTextCulture($chatId, $text);
            exit;
        }

        $keyboard = get_keyboard(Culture::where('name', 'LIKE', "%{$text}%")->pluck('name')->toArray());
        send_keyboard($chatId, $keyboard, 'Виберіть із списка оду культуру яка вам найбільше підходить');
    }

    public function selectCulture($chatId, $text)
    {
        $data = Cache::get($chatId);
        $data['method'] = 'selectCulture';
        if (empty($data['culture'])) {
            $data['culture'] = $text;
        }
        Cache::put($chatId, $data, self::TIME_CACHE);

        $this->sendTextProblemGroup($chatId, $text);
    }


    public function sendTextProblemGroup($chatId, $text)
    {

        if (Problem::where('name', $text)->count() === 1) {
            $this->selectProblem($chatId, $text);
            exit;
        }

        if (Problem::where('name', 'LIKE', "%{$text}%")->count() !== 0) {
            $this->searchProblem($chatId, $text);
            exit;
        }

        if (ProblemGroup::where('name', $text)->count() === 1) {
            $this->selectProblemGroup($chatId, $text);
            exit;
        }

        $keyboard = get_keyboard(ProblemGroup::all()->pluck('name')->toArray());
        send_keyboard($chatId, $keyboard, 'Введіть назву проблеми або виберіть із списка групу в яку входить ваша проблема');
    }

    public function selectProblemGroup($chatId, $text)
    {

        $data = Cache::get($chatId);
        $data['method'] = 'selectProblemGroup';
        $data['problemGroup'] = $text;
        Cache::put($chatId, $data, self::TIME_CACHE);
        $this->sendTextProblem($chatId, $text);
    }


    public function sendTextProblem($chatId, $text)
    {
        $data = Cache::get($chatId);
//        $problemGroup = ProblemGroup::where('name', $data['problemGroup'])->firs();
        $this->test($chatId, 'sendTextProblem');
        $this->test($chatId, (string)$data['problemGroup']);
        $this->test($chatId, (string)$text);
        $this->test($chatId, (string)ProblemGroup::where('name', $data['problemGroup'])->count());


//        $keyboard = get_keyboard($problemGroup->problems->pluck('name')->toArray());
//        $reply_markup = \Telegram::replyKeyboardMarkup([
//            'keyboard'          => $keyboard,
//            'resize_keyboard'   => TRUE,
//            'one_time_keyboard' => TRUE,
//        ]);
//        $response = \Telegram::sendMessage([
//            'chat_id'      => $chatId,
//            'text'         => 'Введіть назву проблеми або виберіть із списка групу в яку входить ваша проблема',
//            'reply_markup' => $reply_markup,
//        ]);
//        $response->getMessageId();
    }

    public function searchProblem($chatId, $text)
    {
        $data = Cache::get($chatId);
        $data['method'] = 'searchProblem';
        Cache::put($chatId, $data, self::TIME_CACHE);

        $keyboard = get_keyboard(ProblemGroup::where('name', 'LIKE', "%{$text}%")->pluck('name')->toArray());
        send_keyboard($chatId, $keyboard, 'Виберіть із списка проблему яка вам підходить');
    }

    public function selectProblem($chatId, $text)
    {

        $data = Cache::get($chatId);
        $data['method'] = 'selectProblem';
        if (empty($data['problem'])) {
            $data['problem'] = $text;
        }
        Cache::put($chatId, $data, self::TIME_CACHE);
    }


    public function test($chatId, $text)
    {

        $response = \Telegram::sendMessage([
            'chat_id' => $chatId,
            'text'    => 'test: ' . $text,
        ]);

        $response->getMessageId();
    }


}
