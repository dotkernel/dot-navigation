# Configuration

After installation, register `dot-navigation` in your project by adding the below line to your configuration aggregator (usually: `config/config.php`):

     Dot\Navigation\ConfigProvider::class,

Locate dot-navigation's distributable config file `vendor/dotkernel/dot-navigation/config/autoload/navigation.global.php.dist` and duplicate it in your project as `config/autoload/navigation.global.php`
