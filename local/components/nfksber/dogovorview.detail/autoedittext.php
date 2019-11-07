<? 
class autoedittext {

    private function setDateString($mons){
        $arr_string_mons =array(
            '01' => 'Января',
            '02' => 'Февраля',
            '03' => 'Марта',
            '04' => 'Апреля',
            '05' => 'Мая',
            '06' => 'Июня',
            '07' => 'Июля',
            '08' => 'Августа',
            '09' => 'Сентября',
            '10' => 'Октября',
            '11' => 'Ноября',
            '12' => 'Декабря',
        );

        return $arr_string_mons[$mons];
    }

    public function replaceTag($TEXT, $arrPropertyUser){
        $mons = date('m');
        $mons = $this->setDateString($mons);
        $data = date('"d" ').$mons.' '.date('Y');
        
        $clearText = str_replace('[дата заключения]', $data, $TEXT);
        $FullName  = $arrPropertyUser["LAST_NAME"].' '.$arrPropertyUser["NAME"].' '.$arrPropertyUser["SECOND_NAME"];
        $clearText = str_replace('[ФИО Подписанта]', $FullName, $clearText);
        $clearText = str_replace('[ФИО Покупателя]', $FullName, $clearText);
        $clearText = str_replace('[ФИО Продавца]', $FullName, $clearText);
        $clearText = str_replace('[ФИО Договородателя]', $FullName, $clearText);
        $clearText = str_replace('[Введите данные]', '', $clearText);
        $clearText = str_replace('[телефон Покупателя]', $arrPropertyUser["PERSONAL_PHONE"], $clearText);

        return $clearText;
    }

}