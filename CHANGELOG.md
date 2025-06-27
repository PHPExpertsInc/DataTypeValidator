## v1.8.1
* **[2025-06-27 00:34:59 CDT]** [BACKPORT] Extracted extractNullable into its own trait shared with SimpleDTO.
* **[2025-06-27 00:40:00 CDT]** [BACKPORT] Refactored nullable property extraction to also support "|null". HEAD -> v1.x

## v1.8.0
* **[2025-04-10 07:42:15 CDT]** [BREAKING BACKPORT] Explicitly assert that extra values are not permitted in Strict mode.
* **[2025-04-10 07:40:57 CDT]** [BACKPORT] Added a mechanism for self-identifying whether a validator is strict or not.

## v1.7.0
* **[2025-03-17 15:21:09 CDT]** [BACKPORT] Added support for mixed types (no validation).

## v1.6.2
* **[2025-03-13 17:05:30 CDT]** [m] Fixed a PHP 8.0+ deprecation.

## v1.6.1
* **[2025-03-13 10:31:59 CDT]** Added a comprehensive test suite for DataTypeValidator::assertIsAType().
* **[2025-03-13 10:25:07 CDT]** Fixed a bug where all strings, arrays, etc. were treated incorrectly as fuzzy bools.

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
