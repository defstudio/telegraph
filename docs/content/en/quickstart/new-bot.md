---
title: 'Creating a new Telegram bot'
menuTitle: 'Creating a new bot'
description: ''
category: 'Quickstart'
fullscreen: true 
position: 20
---


- Go to the [@BotFather](https://t.me/botfather) app on Telegram.

- Send `/newbot`, to start creating a new Bot and setting its name and username.

    <img src="screenshots/new-bot.jpg" />

- Take note of the bot `token`.

    <img src="screenshots/new-bot-token.jpg" />

- Allow the bot to join Telegram groups:

    <img src="screenshots/new-bot-joingroups.jpg" />

- Now you need to choose how much the bot will be able to read from the chats. Send `/setjoingroups` command to @BotFather, and select your bot privacy:

  - **enable**: to handle only `/` commands handling
  - **disable**: to allow the bot to read all messages sent to the chat

    <img src="screenshots/new-bot-setprivacy.jpg" />
