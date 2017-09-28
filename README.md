# UserEngage 2 integration with Magento 2

## First steps

Copy the module folder to &lt;Magento file system owner&gt;/app/code/

## Module enable

To enable available modules, use the following comand:
```
php bin/magento module:enable Userengage_Engage
```

## Module disable
To disable available modules, use the following comand:
```
php bin/magento module:disable Userengage_Engage
```

## Update the database

If you enabled one or more modules, run the following command to update the database:

```
php bin/magento setup:upgreade
```

## Documentation
[Enable or disable modules - Magento](http://devdocs.magento.com/guides/v2.0/install-gde/install/cli/install-cli-subcommands-enable.html)