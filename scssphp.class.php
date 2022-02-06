<?php
/*
 * Автор модуля: Сергей Зверев <element1493@yandex.ru>
 * Библиотека: scssphp 1.10.0 [https://scssphp.github.io/scssphp/]
 * Версия модуля: 1.0.0 (30.01.2022)
 */

require_once "scssphp/scss.inc.php";
use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\OutputStyle;


class dleScssCompiler{
	public $fileHash			= ROOT_DIR.'/engine/cache/scssphp.hash';
	public $fileScss			= 'templates/Default/scss/styles.scss';
	public $fileCss				= 'templates/Default/css/styles.css';
	public $outputStyle			= false;
	public $importPaths 		= false;
	public $sourceMap			= false;
	public $scssHash			= false;

	/**
	 * @param $fileScss
     * @param $fileCss
	 * @param $outputStyle
	 * @param $importPaths
	 * @param $sourceMap
	 * @param $scssHash
     */
	function __construct($fileScss, $fileCss, $outputStyle, $importPaths, $sourceMap, $scssHash) {
		
		$options					= new stdClass();
		$options->fileScss			= $fileScss;
		$options->fileCss			= $fileCss;
		$options->outputStyle		= $outputStyle;
		$options->importPaths		= $importPaths;
		$options->sourceMap			= $sourceMap;
		$options->scssHash 			= $scssHash;
		
		$this->config = $options;
	}
	
	/**
	 * @param $files
	 * @return array
	 */
	public function fileScss($files) {
		$arScss = array();
		$arError = array();
		$arFiles = explode(',', $files);
		
		foreach ($arFiles as $file){
			$file = ltrim($file,'/');
			if (is_file($file) && (strtolower(pathinfo($file,PATHINFO_EXTENSION))=='scss')) {
				$arScss[]= file_get_contents($file);
			}else{
				$arError[] = $file;
			}
		}
		return array('result' => implode("", $arScss),'error' => implode(",", $arError));
	}
	
	/**
	 * @return array
	 * @throws Exception
	 */
	public function compile() {
		
		$return = array();
		/*SCSS Hash*/
		if($this->config->scssHash){
			$scss_hash = hash('md5',file_get_contents($this->config->fileScss));
			$is_hash = (!file_exists($this->file_hash) || ($scss_hash!=file_get_contents($this->file_hash)))?true:false;
		}else{
			$is_hash = true;
		}
				
		if ($is_hash){
			$arScss = $this->fileScss($this->config->fileScss);
			
			if(!empty($arScss['result'])){
				try {
					$compiler = new Compiler();
					
					/*Output Formatting*/
					if($this->config->outputStyle){
						$compiler->setOutputStyle(OutputStyle::COMPRESSED);
					}else{
						$compiler->setOutputStyle(OutputStyle::EXPANDED);
					}
					
					/*Import Paths*/
					if($this->config->importPaths) $compiler->setImportPaths($this->config->importPathss);
					
					/*Source Maps*/
					if($this->config->sourceMap){
						$compiler->setSourceMap(Compiler::SOURCE_MAP_FILE);	
						$compiler->setSourceMapOptions(array(
							'sourceMapURL'		=> $this->config->fileCss.'.map',
							'sourceMapFilename' => $this->config->fileCss,
							'sourceMapBasepath' => ROOT_DIR,
							'sourceRoot'		=> '/'
						));
					}
					
					$result	= $compiler->compileString($arScss['result']);
					
					file_put_contents($this->config->fileCss, $result->getCss());
					/*Source Maps*/
					if($this->config->sourceMap) file_put_contents($this->config->fileCss.'.map', $result->getSourceMap());
					/*SCSS Hash*/
					if($this->config->scssHash) file_put_contents($this->file_hash, $scss_hash);
					
					if(!empty($arScss['error'])){
						$return = array(
							'error' => array(
								'files' => $arScss['error'],
								'compiler' => ''
							)
						);
					}
				} catch (\Exception $e) {
					$return = array(
						'error' => array(
							'files' => ((!empty($arScss['error']))?$arScss['error']:false),
							'compiler' => $e->getMessage()
						)
					);
				}
			}else{
				$return = array(
					'error' => array(
						'files' => $arScss['error'],
						'compiler' => ''
					)
				);
			}
					
			return $return;
		}
	}
}