<?php

namespace App\Entity;


class ChangePwd
{

    private $oldPwd;

    private $newPwd;



    public function getOldPwd(): ?string
    {
        return $this->oldPwd;
    }

    public function setOldPwd(string $oldPwd): self
    {
        $this->oldPwd = $oldPwd;

        return $this;
    }

    public function getNewPwd(): ?string
    {
        return $this->newPwd;
    }

    public function setNewPwd(string $newPwd): self
    {
        $this->newPwd = $newPwd;

        return $this;
    }
}
