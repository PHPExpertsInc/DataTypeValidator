## v3.0.0
* **[2025-04-10 07:42:15 CDT]** Explicitly assert that extra values are not permitted in Strict mode. HEAD -> v3.x
* **[2025-04-10 07:40:57 CDT]** Added a mechanism for self-identifying whether a validator is strict or not.

## v2.1.0
* **[2025-03-17 15:21:09 CDT]** Added support for mixed types (no validation).

## v2.0.0
* **[2025-03-13 11:10:52 CDT]** [m] Readded some fuzzy bool types accidentally removed.
* **[2025-03-13 10:57:54 CDT]** [m] Minor editorial tweaks.
* **[2025-03-13 10:54:02 CDT]** Added a comprehensive test suite for DataTypeValidator::assertIsAType().
* **[2025-03-13 10:11:47 CDT]** [BREAKING] Strings "true" and "false" are no longer considered fuzzy bools.
* **[2025-03-12 19:31:58 CDT]** Added PHP 8.0 method signatures.
* **[2025-03-12 19:08:19 CDT]** Refactored the data types detectors for more succinctness.
* **[2025-03-12 18:58:21 CDT]** Upgraded to PHPUnit v9.6.
* **[2025-03-12 18:55:13 CDT]** Migrated to PHP 8.0-compatible code.
* **[2025-03-12 17:29:39 CDT]** Made PHP v8.0 the minimum PHP version.

## v1.6.0
* **[2025-03-12 16:10:51 CDT]** [m] Removed a very tiny piece of dead code. tag: v1.6.0
* **[2025-03-12 15:45:42 CDT]** [m] Upgraded to modern phpbench.
* **[2025-03-12 15:43:41 CDT]** Installed phpexperts/dockerize to be able to run the project's tests via PHP v7.1.
* **[2025-03-12 15:01:33 CDT]** [m] Added some tests for strict 0.0 float.
* **[2020-07-28 22:39:05 CDT]** about some PHPUnit stuffs peter279k/test_enhancement, test_enhancement

## v1.5.2
* **[2019-07-30 13:13:11 CDT]** Allow nullable and empty arrays of something. hopeseekr/better_nullable_arrays

## v1.5.1
* **[2019-07-28 14:10:07 CDT]** Now filters out the "[]" when checking for arrays of something. hopeseekr/better_arrays

## v1.5.0
* **[2019-05-27 12:30:05 CDT]** Added a .gitattributes.
* **[2019-05-27 12:26:14 CDT]** Added support for validating arrays of something.
* **[2019-05-27 10:33:51 CDT]** Largely refactored how specific objects are validated.


## v1.0.3
* **[2019-05-20 11:44:39 CDT]** Added the ability to retrieve what data type validation logic is used. tag: v1.0.3

## v1.0.2
* **[2019-05-19 21:31:38 CDT]** Removed dead code.
* **[2019-05-19 21:28:52 CDT]** Fixed a bug with filter_var().

## v1.0.1
* **[2019-05-17 07:58:28 CDT]** Added support for nullable data types. hopeseekr/better_nullables
 
## v1.0.0
* **[2019-05-12 13:55:16 CDT]** [m] Tweak the packagist search terms.
* **[2019-05-12 13:43:29 CDT]** Let's see if CodeClimate is so easily confused. hopeseekr/improve_score
* **[2019-05-12 13:29:38 CDT]** Handle an edge case where a rule is not a string. hopeseekr/null_types
* **[2019-05-12 13:14:40 CDT]** Fixed PHP 7.1 support.
* **[2019-05-12 13:08:48 CDT]** Added the license header.
* **[2019-05-12 13:07:34 CDT]** Some cleanup.
* **[2019-05-12 13:07:17 CDT]** Added the ability to specify any data type as nullable with a "?".
* **[2019-05-12 12:38:02 CDT]** Added TravisCI support.
* **[2019-05-12 12:35:42 CDT]** Integrated CodeClimate.
* **[2019-05-12 12:28:09 CDT]** Version 1.0.
