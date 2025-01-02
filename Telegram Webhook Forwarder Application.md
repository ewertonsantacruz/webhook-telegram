# Telegram Webhook Forwarder Application

A web application that forwards Telegram messages to a webhook URL and provides a real-time dashboard for message monitoring. Built with PHP, MySQL, and modern web technologies.

## Features

- Real-time message forwarding from Telegram to webhook
- Message logging in database and files
- Real-time web dashboard with auto-refresh
- Detailed documentation interface
- Error logging and debugging tools
- Secure configuration management

## Prerequisites

1. **Server Requirements:**
   - PHP 7.4+ with MySQL support
   - Apache/Nginx web server
   - SSL certificate (required for Telegram webhooks)
   - MySQL/MariaDB database

2. **Telegram Requirements:**
   - Telegram Bot Token (obtain from @BotFather)
   - HTTPS webhook URL

3. **Development Tools:**
   - Git (optional)
   - Text editor or IDE
   - Command-line access

## Installation

1. **Project Structure:**
```bash
/webhook-telegram/
├── config/
│   └── config.php         # Configuration settings
├── logs/
│   └── .htaccess         # Protect logs directory
├── public/
│   ├── index.html        # Dashboard interface
│   ├── docs.html         # Documentation
│   ├── get_logs.php      # API endpoint for logs
│   ├── test.php          # System test script
│   └── test_webhook.php  # Webhook test script
└── src/
    └── telegram_webhook.php  # Main webhook handler
```

2. **Database Setup:**
```sql
CREATE TABLE telegram_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_id VARCHAR(50),
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

3. **Configuration:**
```php
// config/config.php
return [
    'telegram_bot_token' => 'YOUR_BOT_TOKEN',
    'webhook_url' => 'YOUR_DESTINATION_WEBHOOK_URL',
    'database' => [
        'host' => 'localhost',
        'username' => 'your_username',
        'password' => 'your_password',
        'database' => 'your_database'
    ]
];
```

## Setup Steps

1. **Set File Permissions:**
```bash
chmod 755 .
chmod 755 public src config
chmod 644 public/*.php src/*.php
chmod 666 logs/messages.log
```

2. **Configure Telegram Webhook:**
```bash
curl -F "url=https://your-domain.com/webhook-telegram/src/telegram_webhook.php" \
     https://api.telegram.org/botYOUR_BOT_TOKEN/setWebhook
```

3. **Verify Webhook:**
```bash
curl https://api.telegram.org/botYOUR_BOT_TOKEN/getWebhookInfo
```

## Features

### 1. Message Forwarding
- Automatic forwarding of Telegram messages to configured webhook
- JSON payload with message details:
  - Chat ID
  - Message text
  - Sender information
  - Timestamp

### 2. Message Logging
- Database storage of all messages
- File-based logging for debugging
- Error logging with timestamps

### 3. Web Dashboard
- Real-time message display
- Auto-refresh every 30 seconds
- Manual refresh option
- Clean, responsive interface
- Message history with timestamps

### 4. Documentation Interface
- Installation instructions
- Configuration guide
- Troubleshooting section
- Code examples
- Mobile-friendly layout

## Testing

1. **System Test:**
```bash
curl https://your-domain.com/webhook-telegram/public/test.php
```

2. **Webhook Test:**
```bash
curl https://your-domain.com/webhook-telegram/public/test_webhook.php
```

3. **Database Test:**
```bash
curl https://your-domain.com/webhook-telegram/public/test_db.php
```

## Monitoring

1. **Web Dashboard:**
   - Access at: `https://your-domain.com/webhook-telegram/public/index.html`
   - Shows real-time message updates
   - Displays message history

2. **Documentation:**
   - Access at: `https://your-domain.com/webhook-telegram/public/docs.html`
   - Complete setup and usage guide

## Error Handling

1. **Log Files:**
   - Message logs: `logs/messages.log`
   - PHP errors: `logs/php_errors.log`

2. **Database Logging:**
   - All messages stored in `telegram_logs` table
   - Timestamps for each message
   - Chat ID and message content preserved

## Security Considerations

1. **File Protection:**
   - Protected logs directory
   - Secure configuration file
   - Input sanitization

2. **Database Security:**
   - Prepared statements
   - Error handling
   - Connection management

## Troubleshooting

1. **Common Issues:**
   - Webhook connection problems
   - Database connection errors
   - Permission issues
   - SSL certificate problems

2. **Debugging Tools:**
   - Test scripts
   - Error logs
   - Webhook verification
   - Database connection test

## Support

For issues and support:
1. Check the documentation
2. Review error logs
3. Test with provided test scripts
4. Contact support if needed

---

This application provides a robust solution for forwarding Telegram messages to webhooks while maintaining a comprehensive logging and monitoring system.
