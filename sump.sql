-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Дек 06 2018 г., 15:44
-- Версия сервера: 10.0.34-MariaDB-0ubuntu0.16.04.1
-- Версия PHP: 7.0.32-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- База данных: `tg_bot_backup`
--

-- --------------------------------------------------------

--
-- Структура таблицы `antiflood`
--

CREATE TABLE `antiflood` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `antimat`
--

CREATE TABLE `antimat` (
  `id` int(11) NOT NULL,
  `id_chat` bigint(20) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `antimat_stats`
--

CREATE TABLE `antimat_stats` (
  `id` int(11) NOT NULL,
  `id_user` bigint(20) DEFAULT NULL,
  `nickname` tinytext,
  `count` int(11) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `blacklist_chats`
--

CREATE TABLE `blacklist_chats` (
  `id` int(11) NOT NULL,
  `id_chat` bigint(20) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `crypto_currencies`
--

CREATE TABLE `crypto_currencies` (
  `id` int(11) NOT NULL,
  `id_ticker` int(11) DEFAULT NULL,
  `name` tinytext,
  `symbol` tinytext,
  `website_slug` tinytext
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `id_chat` bigint(20) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `message` text,
  `user_nick` tinytext,
  `chat_name` tinytext,
  `id_message` bigint(20) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `missions`
--

CREATE TABLE `missions` (
  `id` int(11) NOT NULL,
  `title` tinytext,
  `answers` mediumtext
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `quotes`
--

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `id_chat` bigint(20) DEFAULT NULL,
  `username` tinytext,
  `text` text,
  `time` int(11) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_acl`
--

CREATE TABLE `tasks_acl` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_date`
--

CREATE TABLE `tasks_date` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `time_added` int(11) DEFAULT NULL,
  `text` text,
  `day` tinytext,
  `month` tinytext,
  `year` tinytext,
  `hour` tinytext,
  `minute` tinytext,
  `times_executed` int(11) DEFAULT '0',
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tasks_permanent`
--

CREATE TABLE `tasks_permanent` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `time_added` int(11) DEFAULT NULL,
  `text` text,
  `en_mon` tinyint(1) DEFAULT NULL,
  `en_tue` tinyint(1) DEFAULT NULL,
  `en_wed` tinyint(1) DEFAULT NULL,
  `en_thu` tinyint(1) DEFAULT NULL,
  `en_fri` tinyint(1) DEFAULT NULL,
  `en_sat` tinyint(1) DEFAULT NULL,
  `en_sun` tinyint(1) DEFAULT NULL,
  `hour` tinytext,
  `minute` tinytext,
  `times_executed` int(11) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tg_chats`
--

CREATE TABLE `tg_chats` (
  `id` int(11) NOT NULL,
  `id_chat` bigint(20) DEFAULT NULL,
  `title` tinytext
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tg_users`
--

CREATE TABLE `tg_users` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `nick` tinytext
) ENGINE=Aria DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `voice_reminders`
--

CREATE TABLE `voice_reminders` (
  `id` int(11) NOT NULL,
  `id_user` bigint(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `day` tinytext,
  `month` tinytext,
  `year` tinytext,
  `hour` tinytext,
  `minute` tinytext,
  `txt` text,
  `done` tinyint(1) DEFAULT NULL
) ENGINE=Aria DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `antiflood`
--
ALTER TABLE `antiflood`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `antimat`
--
ALTER TABLE `antimat`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `antimat_stats`
--
ALTER TABLE `antimat_stats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `blacklist_chats`
--
ALTER TABLE `blacklist_chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `crypto_currencies`
--
ALTER TABLE `crypto_currencies`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `missions`
--
ALTER TABLE `missions`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `quotes`
--
ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_acl`
--
ALTER TABLE `tasks_acl`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_date`
--
ALTER TABLE `tasks_date`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tasks_permanent`
--
ALTER TABLE `tasks_permanent`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tg_chats`
--
ALTER TABLE `tg_chats`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `tg_users`
--
ALTER TABLE `tg_users`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `voice_reminders`
--
ALTER TABLE `voice_reminders`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `antiflood`
--
ALTER TABLE `antiflood`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `antimat`
--
ALTER TABLE `antimat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `antimat_stats`
--
ALTER TABLE `antimat_stats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `blacklist_chats`
--
ALTER TABLE `blacklist_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `crypto_currencies`
--
ALTER TABLE `crypto_currencies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `missions`
--
ALTER TABLE `missions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `quotes`
--
ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks_acl`
--
ALTER TABLE `tasks_acl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks_date`
--
ALTER TABLE `tasks_date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tasks_permanent`
--
ALTER TABLE `tasks_permanent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tg_chats`
--
ALTER TABLE `tg_chats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `tg_users`
--
ALTER TABLE `tg_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `voice_reminders`
--
ALTER TABLE `voice_reminders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;COMMIT;
