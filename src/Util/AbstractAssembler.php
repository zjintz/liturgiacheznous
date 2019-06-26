<?php

namespace App\Util;

/**
 * \brief      Base clase for concrete assemblers.
 *
 * \details    This class defines the methos used to get the liturgy texts
 *             and convert them to PDF\RTF documments.
 *
 */
abstract class AbstractAssembler
{

    // Force Extending class to define this method
    abstract protected function genSourceRoute($liturgyDate);
    abstract protected function assemble($data, $format = "rtf");


    
    /**
     * \brief      Common method to get the raw data from a url.
     *
     * \param      $url The source to get the data from.
     *
     * \return     return The html text got from the source.
     */
    protected function getRawContent($url)
    {
        $link = curl_init();
        curl_setopt($link, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($link, CURLOPT_URL, $url);
        $data = curl_exec($link);
        curl_close($link);
        return $data;
    }

    /**
     * \brief      Common method to get the raw data from a url.
     *
     * \param      $url The source to get the data from.
     *
     * \return     return The html text got from the source.
     */
    public function getDocument($liturgyDate, $format)
    {
        $sourceRoute = $this->genSourceRoute($liturgyDate);
        $rawContent = $this->getRawContent($sourceRoute);
        $document= $this->assemble($rawContent, $format);
        return $document;
    }
}
