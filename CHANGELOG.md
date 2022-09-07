# Changelog

## 2.0.1 - 2022-09-08

### Added
- Add missing English translations.

### Changed
- Replace deprecated `Craft.postActionRequest()` for JS.

### Fixed
- Fix a type error when cloning objects.

## 2.0.0 - 2022-07-10

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.

## 1.2.2 - 2020-11-19

### Fixed
- Fix not cloning UI elements for all element types.

## 1.2.1 - 2020-09-29

### Fixed
- Fix error when cloning an entry type.

## 1.2.0 - 2020-09-03

### Changed
- Now requires Craft 3.5+.

### Fixed
- Fix error when cloning an entry type in Craft 3.5+.

## 1.1.2 - 2020-04-16

### Fixed
- Fix logging error `Call to undefined method setFileLogging()`.

## 1.1.1 - 2020-04-15

### Changed
- File logging now checks if the overall Craft app uses file logging.
- Log files now only include `GET` and `POST` additional variables.

## 1.1.0 - 2020-01-29

### Added
- Craft 3.4 compatibility.

## 1.0.4 - 2019-11-27

### Added
- Add site cloning support.

## 1.0.3 - 2019-05-18

### Fixed
- Fix error when special characters are in the name for cloned items.

## 1.0.2 - 2019-03-13

### Fixed
- Fix cloner button not entry types screen when only one entry type exists.

## 1.0.1 - 2018-11-28

### Fixed
- Fixed an issue where registering plugin resources caused other plugins to fail catastrophically.

## 1.0.0 - 2018-11-19

- Initial release.
