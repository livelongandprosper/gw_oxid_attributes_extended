# Extended Attributes / Filters

**Features**
- display attribute value via attribut id on details page (oxarticle::getAttributesByIdent())
- every attribute is configurable as "display on details page"
- shows every article of the that has the same attribute value (one attribute is configurable)
- involve attribute value on generating seo urls
- sale filter (RRP is bigger than price)

## Install
- This module has to be put to the folder
\[shop root\]**/modules/gw/gw_oxid_attributes_extended/**

- You also have to create a file
\[shop root\]/modules/gw/**vendormetadata.php**

- add content in composer_add_to_root.json to your global composer.json file and call **composer dumpautoload**

After you have done that go to shop backend and activate module.
