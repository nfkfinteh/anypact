<?
class CFormatHTMLText{

    private static $yandexTurboAllowedTags = '<p><span><a><br><hr><h1><h2><h3><h4><h5><h6><ul><ol><li><table><tbody><thead><th><tr><td><b><strong><i><em><sup><sub><ins><del><small><big><pre><blockquote><figure><img><figcaption>';

    public static function RemoveInvalidTags($string, $allowedTags = false, $removeBreaks = false)
	{
		$string = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $string);

		if($allowedTags)
			$string = strip_tags($string, $allowedTags);
		else
			$string = strip_tags($string);

		if ($removeBreaks)
			$string = preg_replace('/[\r\n\t ]+/', ' ', $string);

		return trim($string);
	}

	public static function TextFormatting($text, $removeTag = array())
	{
		$allowedTags = str_replace($removeTag, "", self::$yandexTurboAllowedTags);
		$text = htmlspecialchars_decode($text);
		$text = self::RemoveInvalidTags($text, $allowedTags, false);
		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = preg_replace('/(\r|\n|\r\n){3,}/', '', $text);
		$text = preg_replace("/&#?[a-z0-9]+;/i","",$text);
		return $text;
    }
    
	public static function TitleFormatting($text)
	{
		$text = htmlspecialchars_decode($text);
		$text = self::RemoveInvalidTags($text);
		$text = preg_replace('/\s\s+/', ' ', $text);
		$text = preg_replace('/(\r|\n|\r\n){3,}/', '', $text);
		$text = preg_replace("/&#?[a-z0-9]+;/i","",$text);
		return self::validCharacters($text);
    }
    
    public static function validCharacters($text)
	{
		$text = preg_replace('/&/', '&amp;', $text);
		$text = preg_replace('/>/', '&gt;', $text);
		$text = preg_replace('/</', '&lt;', $text);
		$text = preg_replace('/"/', '&quot;', $text);
		$text = preg_replace("/'/", '&apos;', $text);
		return $text;
	}
}
?>