<?php
/**
 * Created by PhpStorm.
 * User: latitude-d630
 * Date: 06.03.2017
 * Time: 20:06
 *
 * To Do:
 * 1. Добавить MySQL и оттуда брать ивенты
 * 2. Добавить подписку и из бд брать id пользователей для рассылки
 * 3. еще что то.....
 */

if (!isset($_REQUEST))
{
    return;
}

//Строка для подтверждения Callback API
    $confirmation_token = '12a75f3a';

//Ключ сообщества
    $token = 'fb58547d2c74b5e0f58a7ff2edcf3afabd3cfad5685778d5350cbb17d67251e5f77086301e11330e739db';

    $data = json_decode(file_get_contents('php://input'));

    switch ($data->type) {
        case 'confirmation':
            echo $confirmation_token;
            break;

        case 'message_allow':
            $user_id = $data->object->user_id;
            $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0"));

            $user_name = $user_info->response[0]->first_name;
            $id = $user_info->response[0]->id;

            $request_params = array(
                'message' => "
                Привет, {$user_name}!
                Спасибо что вы подписались на нашего бота.
                Теперь вы можете получать уведомения о новых событиях города!
                Список команд: !events
                
                
                ",
                'user_id' => $user_id,
                'access_token' => $token,
                'v' => '5.0'
            );

            $get_params = http_build_query($request_params);

            file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);


        case 'message_new':
            $user_id = $data->object->user_id;
            $user_info = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids={$user_id}&v=5.0"));

            $user_name = $user_info->response[0]->first_name;

            $request_params = array(
                'message' => "
                Привет, {$user_name}!
                Что бы воспользоваться ботом введи: !events или !события
                ",
                'user_id' => $user_id,
                'access_token' => $token,
                'v' => '5.0'
            );

            $get_params = http_build_query($request_params);

            file_get_contents('https://api.vk.com/method/messages.send?'. $get_params);

            echo('ok');

            break;
}
?>