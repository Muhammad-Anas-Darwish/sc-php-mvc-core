<?php 

namespace silvercodes\phpmvc;
use silvercodes\phpmvc\db\DbModel;

abstract class UserModel extends DbModel {
    abstract public function getDisplayName(): string;
}