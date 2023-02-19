This script is meant for the following use-case:

- You use the BuddyBoss theme/plugin on your site.
- You have additional fields for address such as City / State / Country.
- You installed the BP Maps for Members plugin.
- This maps plugin doesn't use the existing data from those 3 fields. It can only use a Location-type field.
- So what you need is to convert an address such as "Boston, MA, USA" into a geocode "42.40081989999999,-70.749455".

How to use:

- First script is export-users-addresses-as-csv.php. It exports the current address data of all active users, into a CSV file. You will need to provide some correct values to the script so it can run correctly.
- After running the first script, review the second script which is get-geocode.php. It reads the CSV file from the previous script, sends the city/state/country data to the Google Maps API and returns the geocode for each user. It spits out 2 SQL queries for each user: one for adding the geocode to the DB (such as "42.40081989999999,-70.749455") and another for adding the formatted address such as "Boston, MA, USA". You will need to provide some DB values to this script, as well as a Google Maps API key.
- After running the 2nd script, you will have a file with the SQL queries you need to run in your DB.

Notes:

- Always test everything in your Test site before doing any changes on your Live site.
- Always create a backup of your Live site before applying any changes.
