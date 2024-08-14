<?php

namespace App\Helpers\SeederHelpers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

abstract class JsonSeeder
{
    private $tempData = [];
    private $tempDataType = "serialize";
    private $fileContent = [];
    private  $dirName;
    private  $fileName;
    protected $filePath = 'assets/tenant/page-layout/';

    public function setBasePath($base_path)
    {
         $this->filePath = $base_path ?? $this->filePath;
         return $this;
    }

    public function getFile(string $dirName,string $fileName){

        $this->dirName = $dirName;
        $this->fileName = $fileName;

        //write code for get file data
        if($this->checkFileExists()){
            //get file content
            $this->getFileContent();
            return $this;
        }
        return  null;
    }

    public function getColumnData(string|array $column,$timestamp = true){
        return $this->buildColumnData($column,$timestamp);
    }

    public function getColumnDataForDynamicPage(string|array $column,$timestamp = true,$isPage = false){
        return $this->buildColumnData($column,$timestamp,$isPage);
    }

    public function getColumnDataForPageBuilder(string|array $column,$id=null,$timestamp = false,$json_encode=false,$instance=false){

        $data = $this->buildColumnDataById($column,$id,$timestamp);
        if (!isset($data[$column])){
            abort(501,sprintf('%s column not found in the file %s.json'),$column,$this->fileName);
        }

        if ($json_encode === false){
            $unserialize_data = json_decode($data[$column],true);
            if (!isset($unserialize_data['page_builder_addon_id'])){
                $unserialize_data['page_builder_addon_id'] = $id;
            }
            $this->tempData = $unserialize_data;
            $this->tempDataType = "json_decode";
        }else{
            $this->tempData = $data[$column];
            $this->tempDataType = "json_encode";
        }

        return $instance ? $this : $this->tempData;
    }


    public function getColumnDataForWidget(string|array $column,$id=null,$timestamp = false,$serialize=false,$instance=false){

        $data = $this->buildColumnDataById($column,$id,$timestamp);

        if (!isset($data[$column])){
            abort(501,sprintf('%s column not found in the file %s.json'),$column,$this->fileName);
        }

        if ($serialize === false){
            $unserialize_data = unserialize( $this->repairSerializeString($data[$column]));
            if (!isset($unserialize_data['id'])){
                $unserialize_data['id'] = $id;
            }
            $this->tempData = $unserialize_data;
            $this->tempDataType = "unserialize";
        }else{
            $this->tempData = $data[$column];
            $this->tempDataType = "serialize";
        }

        return $instance ? $this : $this->tempData;
    }

    public function filterByColumn(array|string $columns,$lang=null){

        $dataType = $this->tempDataType;
        $data = $dataType === 'unserialize' ? $this->tempData : unserialize(  $this->repairSerializeString($this->tempData)) ;
            //todo check current datatype in this class object
            //todo get current tempdata
        if (is_array($columns)){
            if (!is_null($lang)){
                $buildData = [];
                $hasKey = Str::uuid()->toString();
                foreach ($columns as  $col ){
                    if (array_key_exists($col.'_'.$lang,$data)){
                        $buildData[$hasKey][$col.'_'.$lang] = $data[$col.'_'.$lang];
                    }
                }
                return current($buildData);
            }
            return array_intersect_key($data,array_flip($columns));
        }

        if (!is_null($lang)){
                if (array_key_exists($columns.'_'.$lang,$data)){
                    return $data[$columns.'_'.$lang];
                }
                abort(501,sprintf("%s column not found in the file %s.json",$columns,$this->fileName));
        }
        //todo filter data for single column, return as single string

        if (array_key_exists($columns,$data)){
            return $data[$columns];
        }
        abort(501,sprintf("%s column not found in the file %s.json",$columns,$this->fileName));
    }

    public function replaceByColumn(array $columns,$lang=null,$decode_type='serialize'){

        $dataType = $this->tempDataType;

        if ($decode_type === 'serialize'){
            $data = $dataType === 'unserialize' ? $this->tempData : unserialize(  $this->repairSerializeString($this->tempData)) ;
        }else{
            $data = $dataType === 'json_decode' ? $this->tempData : json_decode( $this->tempData,true) ;
        }

        //todo get current tempdata
        $langPrefix = !is_null($lang) ? "_".$lang : '';
        foreach ($columns as  $col => $value ){
            if (array_key_exists($col.$langPrefix,$data)){
                $data[$col.$langPrefix] = $value;
            }
        }
        $this->tempData = $data;
        return  $this;
    }

    private function buildColumnData(string|array $column,$timestamp,$isPage=false){
        $returnData = null;
        foreach ($this->fileContent as $item){
            if (is_array($column)){
                $identifier = Str::uuid()->toString();

                $data = [
                    'id' => $item?->id
                ];
                if ($timestamp){
                    $data['created_at'] = Carbon::now();
                    $data['updated_at'] = Carbon::now();
                }

                if ($isPage && property_exists($item,'theme_slug')){
                    $data['theme_slug'] = $item?->theme_slug;
                }

                $returnData[$identifier] = $data;
                foreach ($column as $col){

                    if (!$isPage && $col !== 'theme_slug'){
                        $this->throwErrorMessageForColumn($item,$col);
                        $returnData[$identifier][$col] = $item?->$col;
                    }else{
                        $returnData[$identifier][$col] = $item?->$col ?? null;
                    }
                }
            }else{
                if (!$isPage !== 'theme_slug'){
                    $this->throwErrorMessageForColumn($item,$column);
                }

                $data = [
                    'id' => $item?->id,
                    $column => $item?->$column,
                ];
                if ($timestamp){
                    $data['created_at'] = Carbon::now();
                    $data['updated_at'] = Carbon::now();
                }
                if ($isPage && property_exists($item,'theme_slug')){
                    $data['theme_slug'] = $item?->theme_slug;
                }
                $returnData[] = $data;
            }
        }
        return !is_null($returnData) ? array_values($returnData) : null;
    }

    private function decodeContent($content){
        return json_decode($content);
    }
    private function encodeContent($content){
        return json_encode($content);
    }
    public function replaceColumnLanguage(string|array $columName, $languageSlug, $newLanguageSlug)
    {
        foreach ($this->fileContent as $item){
            //check if it is array
            if (is_array($columName)){
                foreach ($columName as $col) ;
                    $this->throwErrorMessageForColumn($item,$col);
                    $item->$col = $this->repalceLangdata($languageSlug,$newLanguageSlug,$item->$col);
            }else{
                $this->throwErrorMessageForColumn($item,$columName);
                $item->$columName = $this->repalceLangdata($languageSlug,$newLanguageSlug,$item->$columName);
            }
        }
        return $this;
    }
    public function replaceColumnLanguage_for_widget(string|array $columName, $languageSlug, $newLanguageSlug)
    {
        foreach ($this->fileContent as $item){
            //check if it is array
            if (is_array($columName)){
                foreach ($columName as $col){
                    $this->throwErrorMessageForColumn($item,$col);
                    $item->$col = $this->repalceLangdata_for_widget($languageSlug,$newLanguageSlug,$item->$col);
                }
            }else{
                $this->throwErrorMessageForColumn($item,$columName);
                $item->$columName = $this->repalceLangdata_for_widget($languageSlug,$newLanguageSlug,$item->$columName);
            }
        }
    }
    public function replaceColumnLanguage_for_page_builder(string|array $columName, $languageSlug, $newLanguageSlug)
    {

        foreach ($this->fileContent as $item){
            //check if it is array
            if (is_array($columName)){
                foreach ($columName as $col) ;
                    $this->throwErrorMessageForColumn($item,$col);
                    $item->$col = $this->repalceLangdata_for_widget($languageSlug,$newLanguageSlug,$item->$col);
            }else{
                $this->throwErrorMessageForColumn($item,$columName);
                $item->$columName = $this->repalceLangdata_for_widget($languageSlug,$newLanguageSlug,$item->$columName);
            }
        }
        return $this;
    }

    public function replaceColumnContent(int $id, string $columName, $newData, string $lang)
    {
        foreach ($this->fileContent as $item){
            if ($item->id === $id){
                $this->throwErrorMessageForColumn($item,$columName);
                $item->$columName = $this->repalceData($item->$columName,$newData,$lang);
            }
        }

        return $this;
    }

    private function throwErrorMessageForColumn($item,$column){
        return  abort_if(!property_exists($item,$column),501,sprintf('(%s) column not found in the %s.json file',$column,$this->fileName));
    }

    private function repalceLangdata($languageSlug,$newLanguageSlug,$columName){
        return str_replace($languageSlug,$newLanguageSlug,$columName);
    }

    private function repalceLangdata_for_widget($newlanguageSlug,$oldLanguageSlug,$columName){
        return  $this->replaceKeySuffixAndFixLength($columName,$oldLanguageSlug,$newlanguageSlug);
    }

    private  function replaceKeySuffixAndFixLength($serializedString, $searchSuffix, $replaceSuffix) {
        // If lengths are the same, just do a simple replace
        if (strlen($searchSuffix) === strlen($replaceSuffix)) {
            $pattern = '/(?<=_)' . preg_quote($searchSuffix, '/') . '(?=":)/';
            $replacement = $replaceSuffix;
            $replaced = preg_replace($pattern, $replacement, $serializedString);
            return $replaced;
        }

        $pattern = '/(?<=_)' . preg_quote($searchSuffix, '/') . '(?=":)/';
        preg_match_all($pattern, $serializedString, $matches, PREG_OFFSET_CAPTURE);
        $offsetAdjustment = 0;
        foreach ($matches[0] as $matchData) {
            $match = $matchData[0];
            $offset = $matchData[1];

            // Adjust the offset by any previous replacement length differences
            $offset += $offsetAdjustment;

            $beforeMatch = substr($serializedString, 0, $offset);
            $afterMatch = substr($serializedString, $offset + strlen($match));

            $serializedString = $beforeMatch . $replaceSuffix . $afterMatch;

            // Adjust the offset adjustment for the next iteration
            $offsetAdjustment += strlen($replaceSuffix) - strlen($searchSuffix);
        }

        return $this->adjustSerializedStringLength($serializedString, $offsetAdjustment);

    }

    function adjustSerializedStringLength($serializedString, $difference) {
        return preg_replace_callback('/s:(\d+):"/', function($matches) use ($difference) {
            $length = (int) $matches[1];
            return 's:' . ($length + $difference) . ':"';
        }, $serializedString);
    }

    private function replaceKeySuffix($serializedData, $fromSuffix, $toSuffix) {
       $pattern = '/"([a-zA-Z0-9]+)' . preg_quote('_'.$fromSuffix, '/') . '"/';
       $replacement = '"$1' .'_'. $toSuffix . '"';
       return preg_replace($pattern, $replacement, $serializedData);
    }

    public function saveFile($withDataParam = false){
        //todo perform save file
        try {
            if ($withDataParam){
                file_put_contents($this->getFilePath(),$this->encodeContent(["data" => $this->fileContent]));
            }else{
                file_put_contents($this->getFilePath(),$this->encodeContent($this->fileContent));
            }
        }catch (\Exception $e){
            return false;
        }
        return true;
    }


    public function saveWidgetFile(){
        //todo perform save file
        try {
            //todo build data by replace file content
            $fileContents = $this->fileContent;
            $dataType = $this->tempDataType;

            $data = $dataType === "serialize" ? unserialize( $this->repairSerializeString($this->tempData)) : $this->tempData;

           $filterDatt =  array_filter($fileContents,function ($item,$key) use ($data,$fileContents){
               if ($item->id == (int) $data['id']){
                   $fileContents[$key]->widget_content = serialize($data);
               }
               return $item;
            },ARRAY_FILTER_USE_BOTH);

           $this->fileContent = $filterDatt;

             file_put_contents($this->getFilePath(),$this->encodeContent(["data" => $this->fileContent]));
        }catch (\Exception $e){
            return false;
        }
        return true;
    }

    public function savePageBuilderFile(){
        //todo perform save file
        try {
            //todo build data by replace file content
            $fileContents = $this->fileContent;
            $dataType = $this->tempDataType;

            $data = $dataType === 'json_decode' ? $this->tempData : json_decode( $this->tempData,true) ;
            $filterDatt =  array_filter($fileContents,function ($item,$key) use ($data,$fileContents){
                if ($item->id == (int) $data['page_builder_addon_id']){
                    $fileContents[$key]->addon_settings = json_encode($data);
                }
                return $item;
            },ARRAY_FILTER_USE_BOTH);
            $this->fileContent = $filterDatt;

            file_put_contents($this->getFilePath(),$this->encodeContent(["data" => $this->fileContent]));
        }catch (\Exception $e){
            return false;
        }
        return true;
    }


    private function checkFileExists(){
       return file_exists($this->getFilePath()) && !is_dir($this->getFilePath(false));
    }

    private function getFilePath($extension = true)
    {
        $ext = $extension ? '.json' : '';
        return $this->filePath .$this->dirName.'/'.$this->fileName.$ext;
    }

    private function getFileContent()
    {
        $decodedData = $this->decodeContent($this->getFileData());
        $decodedData = is_array($decodedData) ? (object) $decodedData : $decodedData;

        abort_if(!property_exists($decodedData,"data"),501,sprintf('data property not found in the file %s.json',$this->fileName));
        $this->fileContent = $decodedData?->data;
        return $this->fileContent;
    }
    private function getFileData(){
        if ($this->checkFileExists()){
            return file_get_contents($this->getFilePath());
        }
        return null;
    }

    private function repalceData($content, $newData,$lang)
    {
     $decodedContent = $this->decodeContent($content);
     abort_if(!property_exists($decodedContent,$lang),501,sprintf('%s not found in the file',$lang));
     $decodedContent->$lang = $newData;

     return $this->encodeContent($decodedContent);

    }

    private function buildColumnDataById(array|string $column, mixed $id, mixed $timestamp)
    {
        $allData = $this->getColumnData($column,$timestamp);

        return current(array_filter($allData,function ($item) use($id){
            return $item['id'] ==  $id;
        }));
    }

    private function repairSerializeString($value)
    {

        return preg_replace_callback ( '!s:(\d+):"(.*?)";!', function($match) {
            return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
        },$value );
    }


}
