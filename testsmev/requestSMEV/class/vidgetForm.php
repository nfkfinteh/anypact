<?php
class vidgetForm {
    
    private function getTemplateVidget($arrParams){
        if(!empty($arrParams['TYPE'])){
            $type = $arrParams['TYPE'];
        }else{
            $type = 'text';
        }

        $htmlcode  = '<div class="form-group" style="margin:20px 0;">';        
        $htmlcode .='<label for="'.$arrParams['ID'].'">'.$arrParams['TEXT'].'</label>';
        $htmlcode .= '<input type="'.$type.'" class="form-control" name="'.$arrParams['ID'].'" id="'.$arrParams['ID'].'">';
        $htmlcode .= '</div>';
        return $htmlcode;
    }
    
    public function renderFilds($arrFilds){
        foreach ($arrFilds as $item) {
            echo $this->getTemplateVidget($item);
        }
    }
}