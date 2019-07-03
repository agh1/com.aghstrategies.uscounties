# US County Loader

The US Counties extension for CiviCRM allows an easy method to load all the
counties in the United States.  Because they are country-specific and so
numerous, counties are not loaded by default in CiviCRM.

This extension also provides a model for counties and other subdivisions of
states and provinces in other countries.

## Using the extension

Enabling the extension runs the county loader, which loads all the counties in
each state if they aren't already in your database.  From that point, the
extension does nothing.  You can even disable the extension, and the counties
will stay there.  Uninstalling the extension also does nothing, for an important
reason: it could cause data loss by deleting data stored in addresses.

In addition to using the extension to load the counties, you'll want to go to
Administer - Localization - Address Settings and check County under Address
Editing, which will display the County field when you edit a contact's record.

## History

Counties existed in CiviCRM since very early in the project, and they were
linked to the appropriate states.  The database is shipped with five counties
from California, and many users loaded counties for specific states.  However,
the interface didn't chain county selections with the states, and many county
names exist in multiple states.  This chaining was added for CiviCRM 3.4/4.0,
but because not everyone needs over 3,000 U.S. counties in their database, the
CiviCRM database still only ships with the five counties.

Starting then, the counties were included in CiviCRM in a gzipped SQL file, but
many users find it difficult to unzip and load the file.  This approach aims to
make it an easy process for all site administrators to implement counties.

## Other Countries

You can modify this extension for your country's counties or county equivalents.

 1. Look in the `civicrm_country` table in your site's database for the `id` and
    `iso_code` for the country you would like to provide counties for.

 2. Look in the `civicrm_state_province` table in your site's database to find
    the `name` values for each of the rows where `country_id` equals the `id`
    from `civicrm_country`.  You can use the following query (where `123` is the
    `id` you got in step 1):

    ```sql
    SELECT name FROM civicrm_state_province WHERE country_id = 123
    ```

    These are the names of your country's states as far as CiviCRM is concerned.

 3. Replace the contents of `counties.json` with a JSON object in the following
    format:

    ```json
    {
      "FirstISOCode": {
        "StateName1": [
          "County1A",
          "County1B"
        ],
        "StateName2": [
          "County2A",
          "County2B"
        ]
      },
      "SecondISOCode": {
        "ProvinceName1": [
          "Division1A",
          "Division1B",
        ],
        "ProvinceName2": [
          "Division2A",
          "Division2B"
        ]
      }
    }
    ```

    If a state, province, or equivalent has no counties, you do not need to list
    it.

## Changelog

### Version 2.0

Change to load counties according to state names rather than IDs.  IDs have not
been consistent for many countries' states and provinces.

Counties have been moved to a separate JSON file for ease of reading the
extension and ease of applying the extension to other countries.

The county list has also been updated to reflect changes since it was compiled.

### Version 1.1

Compatibility with 4.6 and 4.7 (no changes to code or counties)

### Version 1.0

Initial version
