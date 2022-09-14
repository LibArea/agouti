<?php

declare(strict_types=1);

use Hleb\Constructor\Handlers\Request;
use App\Models\ActionModel;

class Access
{
    public static function limitForMiddleware()
    {
        $type = Request::get('type');

        if (UserData::checkAdmin()) {
            return true;
        }

        if (self::limitingMode() === false) {
            Msg::add(__('msg.silent_mode',), 'error');
            redirect('/');
        }

        // TODO: Изменим поля в DB, чтобы использовать limitContent для messages и invitation: 
        if (in_array($type, ['post', 'amswer', 'comment', 'item', 'team'])) {
            if (self::limitContent($type) === false) {
                Msg::add(__('msg.limit_day'), 'error');
                redirect('/');
            }
        }
    }

    /**
     * Stop changing (adding) content if the user is frozen (silent mode)
     *
     * Остановим изменение (добавление) контента если пользователь заморожен (немой режим)
     */
    public static function limitingMode(): bool
    {
        if (UserData::getLimitingMode() == 1) {
            return false;
        }
        return true;
    }

    /**
     * From what TL level is it possible to create content.
     *
     * In config: tl_add_post
     */
    public static function limitContent(string $type): bool
    {
        /**
         * From what TL level is it possible to create content.
         *
         * С какого уровня TL возможно создавать контент.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels.tl_add_' . $type)) == false) {
            return false;
        }

        /**
         * Limit per day for the level of confidence, taking into account coefficients.
         *
         * Лимит за сутки для уровня доверия с учетом коэффициентов.
         *
         * In config: perDay_post
         */
        $сount = ActionModel::getSpeedDay(UserData::getUserId(), $type);

        $total = config('trust-levels.perDay_' . $type) * config('trust-levels.multiplier_' . UserData::getUserTl());

        if ($сount >= floor($total)) {
            return false;
        }

        return true;
    }

    /**
     * Trust Level Sharing.
     *
     * Общий доступ для уровня доверия.
     */
    public static function trustLevels(int $trust_level): bool
    {
        if (UserData::getUserTl() < $trust_level) {
            return false;
        }

        return true;
    }

    /**
     * Time limits after posting.
     *
     * Лимиты на время после публикации.
     */
    public static function limiTime(string $adding_time, int $limit_time = null): bool
    {
        if ($limit_time == true) {
            $diff = strtotime(date("Y-m-d H:i:s")) - strtotime($adding_time);
            $time = floor($diff / 60);

            if ($time > $limit_time) {
                return false;
            }
        }

        return true;
    }

    /**
     * Content type, data array and how much time can be edited.
     *
     * Тип контента, массив данных и сколько времени можно редактировать.
     */
    public static function author(string $type_content, array $info_type, int $limit_time = 30): bool
    {
        if (UserData::checkAdmin()) {
            return true;
        }

        /**
         * If the author's Tl has been downgraded.
         *
         * Если Tl автора было изменено на понижение.
         *
         * In config: tl_add_post
         */
        if (self::trustLevels(config('trust-levels.tl_add_' . $type_content)) === false) {
            return false;
        }

        /**
         * Only the author has access.
         *
         * Доступ получает только автор.
         */
        if ($info_type[$type_content . '_user_id'] != UserData::getUserId()) {
            return false;
        }

        if (self::limiTime($info_type[$type_content . '_date'], $limit_time) === false) {
            return false;
        }

        return true;
    }
}
