Images Downloader
=========================

Download free and no copyright high resolution images with your console.

You can automatically download images from:
- [unsplash.com](http://unsplash.com) (10 new photos every 10 days)
- ...

## Requirements
- Php 5.3
- Composer

## Setup

1. Clone this repository
2. Open your terminal
3. Go into Images Downloader directory and execute `composer install` to download all dependencies
   

## Usage
Downloader will support more sites, so you have to tell from which site you want to download images.

He's an example with `unplash` command:

1. Go to the `bin/` folder in your terminal
2. Run `php download unsplash --test` to test if all works fine by downloading only 1 file. Files are stored by default in `downloads/unsplash/`
3. Run `php download unsplash --latest` to get only the latest files (last 100)
4. Run `php download unsplash` to get them all (more than 300 photos...)


## Credits
Created by Arnaud J. Inspired by [pjabang1/xam-unsplash-image-downloader](https://github.com/pjabang1/xam-unsplash-image-downloader).