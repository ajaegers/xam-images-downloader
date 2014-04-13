<?php

namespace Xam\Unsplash\Downloader;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class ImageDownloader {

    const DIR = '../downloads/unsplash/'; // default destination directory relative to /bin dir

    /**
     *
     * @var type 
     */
    private $client;

    /**
     *
     * @var string 
     */
    private $path;

    /**
     * 
     * @param type $client
     */
    public function __construct($client = null) {
        $this->client = $client;
    }

    /**
     * 
     * @param type $total
     * @return string
     */
    private function getUrl($start) {
        if ($start) {
            return $this->getPath() . '?start=' . $start;
        }
        return $this->getPath();
    }

    /**
     * 
     * @param mixed null|int $start
     * @return \DOMDocument
     */
    public function getDocument($start = null) {
        $dom = new \DOMDocument();
        $dom->strictErrorChecking = FALSE;

        try {
            @$dom->loadHTMLFile($this->getUrl($start));
        } catch (\Exception $e) {
            
        }

        return $dom;
    }
    

    /**
     * 
     * @param \DOMDocument $document
     * @param type $path
     */
    public function getImageUrls(\DOMDocument $document, $path = '//photo-link-url') {
        $finder = new \DomXPath($document);
        $return = array();

        $nodes = $finder->query($path);
        foreach ($nodes AS $node) {
            $return[] = $node->nodeValue;
        }
        return $return;
    }


    /**
     * 
     * @param \DOMDocument $document
     * @param type $path
     */
    public function getImageCaptions(\DOMDocument $document, $path = '//photo-caption') {
        $finder = new \DomXPath($document);
        $return = array();

        $nodes = $finder->query($path);
        foreach ($nodes AS $node) {
            $return[] = $node->nodeValue;
        }
        return $return;
    }

    /**
     * 
     * @param \DOMDocument $document
     * @param type $path
     */
    public function getPosts(\DOMDocument $document, $path = '//post') {
        $finder = new \DomXPath($document);
        $return = array();

        $nodes = $finder->query($path);
        foreach ($nodes AS $node) {
            $return[] = $node;
        }
        return $return;
    }

    /**
     * 
     * @param type $from
     * @param type $to
     * @param type $fileName
     */
    public function download($src, $downloadDirectory, $fileName) {
        if (!file_exists($downloadDirectory)) {
            throw new \Xam\Unsplash\Exception\UnsplashException($downloadDirectory . ' does not exist');
        }

        if($fileName){
            $fileName = $this->hyphenize($fileName);
        }
        else {
            $fileName = uniqid();
        }

        $fileName   = $fileName. '.jpg';
        $file       = $downloadDirectory . '/' . $fileName;

        if(file_exists($file)) { // download only new files
            return 'Exists: '.$fileName;
        }

        $response = $this->getClient()->get($src)
                ->setResponseBody($file)
                ->send();
        return 'New:    '.$fileName;
    }

    public function getClient() {
        return $this->client;
    }

    /**
     * 
     * @param \Guzzle\Http\Client $client
     * @return \Unsplash\Downloader\ImageDownloader
     */
    public function setClient(\Guzzle\Http\Client $client) {
        $this->client = $client;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getTotal() {
        $document = $this->getDocument();
        $finder = new \DomXPath($document);
        $totalNode = $finder->query('//posts/@total');
        foreach ($totalNode AS $node) {
            $total = (int) $node->nodeValue;
        }
        return  $total;
    }

    /**
     * 
     * @return string
     */
    public function getPath() {
        return $this->path;
    }

    /**
     * 
     * @param string $path
     * @return \Xam\Unsplash\Downloader\ImageDownloader
     */
    public function setPath($path) {
        $this->path = $path;
        return $this;
    }

    /**
     * Hyphenize a string
     * @param string $string
     * @return string
     */
    public function hyphenize($string) {
        return 
            strtolower(
              preg_replace(
                    array('#[\\s-]+#', '#[^A-Za-z0-9\. -]+#', '#-+#'),
                    array('-', '', '-'),
                      strip_tags(urldecode($string))
                )
            );
    }

}

?>
