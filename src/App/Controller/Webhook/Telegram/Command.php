<?php

namespace App\Controller\Webhook\Telegram;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Client as TelegramClient;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\Message as TelegramMessage;

final class Command
{
    /**
     * @param TelegramClient|BotApi $bot
     *
     * @return void
     */
    public static function configure(TelegramClient $bot): void
    {
        $bot->command(
            'start',
            function (TelegramMessage $a_message) use ($bot) {
                $welcome_message = <<<MARKDOWN
Hey there. Welcome to Crypto Insights bot. You can use bot commands to get some insights, predictions and recommendations for your favorite cryptos.

Use /help to know available commands.
MARKDOWN;
                $bot->sendMessage($a_message->getChat()->getId(), $welcome_message, 'Markdown');
            }
        );

        $bot->command(
            'help',
            function (TelegramMessage $a_message) use ($bot) {
                $help_message = <<<MARKDOWN
I can give you some forecast, analysis and insights using historical data and sentiment analysis from several sources.

*Insights*

/insights - Will ask you for a currency / crypto pair to give some insights based on last changes in the valuation.
MARKDOWN;
                $bot->sendMessage($a_message->getChat()->getId(), $help_message, 'Markdown');
            }
        );

        $bot->command(
            'insights',
            function (TelegramMessage $message) use ($bot) {
                $chat_id = $message->getChat()->getId();

                $bot->sendMessage(
                    $chat_id,
                    'Ok, select base currency.',
                    null,
                    false,
                    null,
                    new InlineKeyboardMarkup(
                        [
                            [
                                ['text' => 'USD', 'callback_data' => json_encode(['method' => 'insights_ask_stock', 'currency' => 'USD'])],
                                ['text' => 'EUR', 'callback_data' => json_encode(['method' => 'insights_ask_stock', 'currency' => 'EUR'])]
                            ]
                        ]
                    )
                );
            }
        );
    }
}