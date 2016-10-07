<?php

class Select{

    private static $label = true;

    static function setLabel($val){
        self::$label = false;
    }

    static function Client(){
        $get = DtClientsElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Client -- ';
        foreach ($get as $key => $row) {
            $select[$row['dc_id']] = $row['dc_cli_name']." (".$row['dc_cli_num'].")";
        }
        return $select;
    }

    static function ContactPerson(){
        $get = DtContactsElq::active()->get();
        if(self::$label) $select =  ["" => ' -- Select Contact -- '];
        foreach ($get as $key => $row) {
            $select[$row['dcon_id']] = $row['dcon_name'].($row->DtClientsElq ? " (".$row->DtClientsElq['dc_cli_name'].")" : "");
        }
        return $select;
    }

    static function Project(){
        $get = DtProjectsElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Project -- ';
        foreach ($get as $key => $row) {
            $select[$row['dpro_id']] = $row['dpro_name']." (".$row->DtClientsElq['dc_cli_name'].")";
        }
        return $select;
    }
    
    static function Departement($filter = false){
        $get = MrDepartementElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Departement -- ';
        foreach ($get as $key => $row) {
            $select[$row['mdep_id']] = $row['mdep_name'];
        }
        return $select;
    }

    static function DocType($filter = false){
        $get = MrDocTypeElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Document Type -- ';
        foreach ($get as $key => $row) {
            if($filter){
                if(in_array($filter, explode(",", $row['mdoc_mat_id'])))
                    $select[$row['mdoc_id']] = $row['mdoc_name'];
            }else{
                $select[$row['mdoc_id']] = $row['mdoc_name'];
            }
        }
        return $select;
    }

    static function ProductType(){
        $get = MrProdTypeElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Product Type -- ';
        foreach ($get as $key => $row) {
            $select[$row['mpt_id']] = $row['mpt_name'];
        }
        return $select;
    }

    static function AccessType(){
        $get = MrAccessTypeElq::active()->get();
        if(self::$label) $select[""] =  ' -- Select Access Type -- ';
        foreach ($get as $key => $row) {
            $select[$row['mat_id']] = $row['mat_name'];
        }
        return $select;
    }

}