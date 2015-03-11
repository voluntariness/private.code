<?php

class device {

    public function index () 
    {

        if ($kind = Input::get('search_kind')) {

            $query->whereIn('kind', $kind);

        }

        if ($user = Input::get('search_user')) {
            //$sql = "SELECT device_id AS id
            //        FROM record
            //        WHERE type IN ('assign', 'rent')
            //            AND NOW() >= sdate
            //            AND (NOW() < edate OR edate IS NULL)
            //            AND type_id 
            //        GROUP BY type_id ";
            /* 取得設備記錄中, 移轉, 借出廠商對象為 $user 成員的記錄 */
            $records = Record::select('device_id')
                ->whereIn('type_id', $user)
                ->whereIn('type', ['assign', 'rent'])
                ->where('sdate', '<=', date('Y/m/d'))
                ->where(function ($query) {
                    $query->where('edate', '>=', date('Y/m/d'))
                        ->orWhereNull('edate');
                })
                ->orderBy('device_id');
            $did = [];
            foreach ($records as $d) {
                $did[] = $d->device_id;
            }
            if (count($did)) {
                $query->whereIn('device_id', $did);
            }
        }

        if ($item = Input::get('search_item')) {

            $query->whereIn('item_id', $item);

        }

    }

}

class ajax {


    public function queryDetail() 
    {
        $query = Input::get('query');
        $device = Device::where('sn', $query)->first() ?: false;
        return $this->requestSuccess(['device' => $device]);
    }

    public function findDevice ()
    {
        $sn = Input::get('sn');
        $device = Device::where('sn', $sn)->first() ?: false;

    }

    /* 用來借出的設備 , (查詢移轉的設備) */
    public function toRentDevice() 
    {
        $sn = Input::get('sn');
        if ($device = Device::where('sn', $sn)->first()) {
            $record = Record::where('device_id', $device->id)
                ->orderBy('sdate')
                ->first() 
                    ?: new Record;
            /* 
            if ($record->type === 'rent' && strtotime($record->edate) > strtotime(date('Y/m/d'))) {

            }

        } else {
            return $this->requestFail(['message' => '查無設備資料']);
        }


    }

}



class record {

    /* 借入記錄 */
    public static function newLease($query, $devices, $lease)
    {
        $record = new Record;
        $record->device_id = $device->id;
        $record->type = 'lease';
        $record->type_id = $lease->id;
        $reocrd->sdate = date('Y/m/d');
        $record->edate = null;
        $record->save();

    }
    /* 借出記錄 */
    public static function newRent($query, $devices, $rent)
    {
        foreach ($devices as $d) {
            $record = new Record;
            $record->device_id = $d->id;
            $record->type = 'rent';
            $record->type_id = $rent->id;
            $reocrd->sdate = date('Y/m/d');
            $record->edate = null;
            $record->save();
        }
    }
    /* 設備移轉 */
    public static function newAssign($query, $devices, $user)
    {
        $record = new Record;
        $record->device_id = $device->id;
        $record->type = 'assign';
        $record->type_id = $user->id;
        $reocrd->sdate = date('Y/m/d');
        $record->edate = null;
        $record->save();
    }

}


class rent {




    public function saveRent () 
    {

        $id = Input::get('id');

        $rent = Rent::find($id) ?: new Rent;

        $rent->user_id  = Input::get('user_id');
        $rent->memo     = Input::get('memo');

        $devices = Input::get('detail_id');

        try {

            $rent->save();

            Record::newRent($devices, $rent);


        } catch ($e) {

        }



    }

}


