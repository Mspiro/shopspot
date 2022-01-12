# The Better Mega Menu Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [\[Unreleased\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.x)

## [\[8.x-1.6\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.6) - 2021-11-08
### Fixed
- Issue [#3238342](https://www.drupal.org/project/tb_megamenu/issues/3238342) by knaffles, HeatherC7474, henry.odiete: Unable to tab through sub menus for ADA
- Issue [#3221150](https://www.drupal.org/project/tb_megamenu/issues/3221150) by knaffles, mattbloomfield, themodularlab, BertVivie: Submenu not rendering
- Issue [#3244915](https://www.drupal.org/project/tb_megamenu/issues/3244915) by nironan: Accessibility: invalid states for multilingual sites

## [\[8.x-1.5\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.5) - 2021-09-24
### Fixed
- Issue [#3238679](https://www.drupal.org/project/tb_megamenu/issues/3238679) by smortimore, themodularlab: HTML entities not processed correctly
- Issue [#3238721](https://www.drupal.org/project/tb_megamenu/issues/3238721) by nironan, themodularlab: Version 1.4 ignores languages
- Issue [#3238650](https://www.drupal.org/project/tb_megamenu/issues/3238650) by bmunslow, themodularlab, leisurman, maxwellkeeble, StevenPatz, knaffles, quondam: Site Inaccessible after updating to 1.4
- Other fixes: Updating changelog with security releases plus details on 1.5.  Also reverts the `$vars['link']['title_translate']` / `link.title_translate` variables found on line 583 of tb_megamenu.module and line 20 of tb-megamenu-item.html-twig to avoid issues with users who have overridden twig template and/or preprocess functions.

## [\[8.x-1.4\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.4) - 2021-09-21
### Security
- [SA-CONTRIB-2021-038](https://www.drupal.org/sa-contrib-2021-038) by FeyP, knaffles: Cross Site Scripting, Information Disclosure, Multiple vulnerabilities
- [SA-CONTRIB-2021-039](https://www.drupal.org/sa-contrib-2021-039) by FeyP, themodularlab, greggles, justAChris, knaffles: Cross Site Scripting
- [SA-CONTRIB-2021-040](https://www.drupal.org/sa-contrib-2021-040) by FeyP, knaffles: Cross Site Request Forgery
- [SA-CONTRIB-2021-041](https://www.drupal.org/sa-contrib-2021-041) by FeyP, henry.odiete, quondam: Access bypass

## [\[8.x-1.3\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.3) - 2021-08-11
### Fixed
- Issue [#3227571](https://www.drupal.org/project/tb_megamenu/issues/3227571) by diamondsea: Version Information in tb_megamenu.info.yml file should be removed

## [\[8.x-1.2\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.4) - 2021-07-07
### Fixed
- Fixed typos related to changelog.

## [\[8.x-1.1\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.1) - 2021-07-07
### Fixed
- Issue [#3222460](https://www.drupal.org/project/tb_megamenu/issues/3222460) by firfin: PHP error after enabling module (syntax error, unexpected ')' in ...Plugin/Derivative/TBMegaMenuBlock.php )

## [\[8.x-1.0\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0) - 2021-06-25
### Fixed
- Issue [#3218483](https://www.drupal.org/project/tb_megamenu/issues/3218483) by themodularlab, knaffles: Opt into Security Policy Coverage
- Issue [#3218482](https://www.drupal.org/project/tb_megamenu/issues/3218482) by pcfoster, knaffles: Keydown listener interferes with use of spacebar
- Issue [#3204998](https://www.drupal.org/project/tb_megamenu/issues/3204998) by themodularlab, crasx, quondam: TB Megamenu doesnt work when logged in with admin toolbar enabled
- issue [#3219595](https://www.drupal.org/project/tb_megamenu/issues/3219595) by nironan, themodularlab: Accessibility: invalid roles for multilingual sites
- Issue [#3186612](https://www.drupal.org/project/tb_megamenu/issues/3186612) by themodularlab, quondam, knaffles: Security Advisory Coverage
  Security Review
- Issue [#3198123](https://www.drupal.org/project/tb_megamenu/issues/3198123) by arshadkhan35: Drupal 9.1 install crash undefined function menu_ui_get_menus()
- Issue [#3198116](https://www.drupal.org/project/tb_megamenu/issues/3198116) by quondam: Add Configure link on Extend page
- Issue [#3199343](https://www.drupal.org/project/tb_megamenu/issues/3199343) by nironan: Allow compatibility with webprofiler
- Issue [#3199456](https://www.drupal.org/project/tb_megamenu/issues/3199456) by nironan: Performance: do not load the menu tree multiple times

## [\[8.x-1.0-rc3\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0-rc3) - 2021-02-11
### Fixed

- Issue [#3196330](https://www.drupal.org/project/tb_megamenu/issues/3196330) by quondam: Uncaught ReferenceError: value is not defined
- Issue [#3196569](https://www.drupal.org/project/tb_megamenu/issues/3196569) by quondam: TypeError: Argument 1 passed to Drupal\tb_megamenu\TBMegaMenuBuilder::editColumnConfig() causes site crash
- Issue [#3195480](https://www.drupal.org/project/tb_megamenu/issues/3195480) by quondam: Improve messaging in toolbox admin UI

## [\[8.x-1.0-rc2\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0-rc2) - 2021-01-28
### Fixed

- Issue [#3195191](https://www.drupal.org/project/tb_megamenu/issues/3195191) by quondam: WSOD with error related to null block_id

## [\[8.x-1.0-rc1\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0-rc1) - 2021-01-27

This is the first 8.x-1.x Release Candidate!  This version contains the required
fixes needed for Drupal Security Policy coverage.  The goal will be to apply for
coverage with this branch and ultimately roll in into an official and stable
1.0.0 version of The better menu.

### Changed

- Changed README.md to include CHANGELOG notes.
- Issue [#3131180](https://www.drupal.org/project/tb_megamenu/issues/3131180) by quondam, Suresh Prabhu Parkala: Drupal coding standards,
2021-01-22
- Issue [#3183288](https://www.drupal.org/project/tb_megamenu/issues/3183288) by quondam, timotej-pl, Scott Weston, themodularlab: Replace
  calls to \Drupal with Dependency Injection, 2021-01-19
- Issue [#3192235](https://www.drupal.org/project/tb_megamenu/issues/3192235) by knaffles, andrewozone: Accessibility Upgrades, 2021-01-19

### Deprecated

- Deprecated CHANGELOG.txt in favor of CHANGELOG.md

### Removed

- Removed CHANGELOG.txt in favor of CHANGELOG.md

### Fixed

- Issue [#2965871](https://www.drupal.org/project/tb_megamenu/issues/2965871) by quondam, gaurav.bajpai, andrewozone: When Parent menu
disabled Submenu getting shifted to previous parent, 2020-11-20
- Issue [#3194239](https://www.drupal.org/project/tb_megamenu/issues/3194239) by quondam, knaffles: Getting a 500 error when menu items are
reordered and then saved, 2021-01-22

### Security

- Issue [#3186616](https://www.drupal.org/project/tb_megamenu/issues/3186616) by knaffles, andrewozone, quondam, themodularlab: Security Advisory Coverage
no code other than release prep, adding because this is the parent security
coverage issue.
- Issue [#3186612](https://www.drupal.org/project/tb_megamenu/issues/3186612) by quondam: Security Advisory Coverage - Security Review

## [\[8.x-1.0-beta2\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0-beta2) - 2020-10-02
### Changed
- Issue [#3174475](https://www.drupal.org/project/tb_megamenu/issues/3174475) by John.nie: Array and string offset access syntax with
  curly braces is deprecated

### Fixed
- Issue [#3174476](https://www.drupal.org/project/tb_megamenu/issues/3174476) by John.nie, themodularlab: Render #post_render callbacks must
  be methods of a class that implements
  \Drupal\Core\Security\TrustedCallbackInterface or be an anonymous function.
- Issue [#3174465](https://www.drupal.org/project/tb_megamenu/issues/3174465) by John.nie: Mega menu missing config_export definition
  in its annotation.
- Issue [#3172977](https://www.drupal.org/project/tb_megamenu/issues/3172977) by Ramya Balasubramanian, dev.patrick: Unwanted special
  characters in hook help.

## [\[8.x-1.0-beta1\]](https://www.drupal.org/project/tb_megamenu/releases/8.x-1.0-beta1) - 2020-09-11
### Added
- Added README.md
- Issue [#3095820](https://www.drupal.org/project/tb_megamenu/issues/3095820) by themodularlab, knaffles, RuslanP: D8 Accessibility Upgrades

### Changed
- Issue [#3002715](https://www.drupal.org/project/tb_megamenu/issues/3002715) by i_g_wright, themodularlab, mellowtothemax: Admin permissions
  set too high

### Fixed
- Issue [#3149011](https://www.drupal.org/project/tb_megamenu/issues/3149011) by Project Update Bot, themodularlab: Automated Drupal 9
  compatibility fixes
- Reverting CSS related to accessibility on Issue #3095820
- Issue [#2882051](https://www.drupal.org/project/tb_megamenu/issues/2882051) by knaffles, maithili11: Top level drop downs do not work on
  touch devices when the menu item path is set to <nolink>
- Issue [#3095820](https://www.drupal.org/project/tb_megamenu/issues/3095820) Restore missing snippet for left arrow. Also fix issue where
  <nolink> top level menu items are not focusable using left/right arrow keys.
- Issue [#2952410](https://www.drupal.org/project/tb_megamenu/issues/2952410) by ankitjain28may, SivaprasadC: Typo in
  css/tb_megamenu.default.css
- Issue [#2982006](https://www.drupal.org/project/tb_megamenu/issues/2982006) by brahmjeet789, Neetika K, Vidushi Mehta: Hook help is missing
- Issue [#2921195](https://www.drupal.org/project/tb_megamenu/issues/2921195) by Zemelia, pafa7a: Missing array keys cause notices
- Issue [#2996857](https://www.drupal.org/project/tb_megamenu/issues/2996857) by knyshuk.vova: Incorrect contextual link generation leads to
  an error.
- Issue [#3045390](https://www.drupal.org/project/tb_megamenu/issues/3045390) by RuslanP, knaffles: Module Description: add dot to the end of
  the sentence.

