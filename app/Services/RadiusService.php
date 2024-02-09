<?php 

namespace App\Services;

use App\Models\radCheck;
use App\Models\radUserGroup;
use App\Models\radReply;
use App\Models\RadiusIP;

class RadiusService
{
    protected $check_attribute = "Cleartext-Password";
    protected $reply_attribute = "Framed-IP-Address";
    protected $check_op = ":=";
    protected $reply_op = "=";
    protected $group_groupname = "NBN-UNLIMITED";
    protected $group_priority = 0;

    // protected $radiusIP

    public function saveData(array $data)
    {
        // Create new instances of the Radius database models
        $radCheck = new radCheck;
        $radReply = new radReply;
        $radUserGroup = new radUserGroup;
        $radiusIP = RadiusIP::find($data['radiusIP_id']);

        // Map fields from the request.
        $radCheck->username = $data['rad_user'];
        $radCheck->attribute = $this->check_attribute;
        $radCheck->op = $this->check_op;
        $radCheck->value = $data['rad_pass'];

        // Map fields from the request.
        $radReply->username = $data['rad_user'];
        $radReply->attribute = $this->reply_attribute;
        $radReply->op = $this->reply_op;
        $radReply->value = $data['rad_ip'];

        // Map fields from the request.
        $radUserGroup->username = $data['rad_user'];
        $radUserGroup->groupname = $this->group_groupname;
        $radUserGroup->priority = $this->group_priority;

        // Update RadiusIP database.
        $radiusIP->buroflow_reference = $data['buroflow_reference'];

        dd($radiusIP->buroflow_reference);
        // Save to the databases.
        // $radCheck->save();
        // $radReply->save();
        // $radUserGroup->save();
        // $radiusIP->save();
    }

}
