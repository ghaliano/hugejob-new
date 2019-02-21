<?php
namespace App\Services;
use Doctrine\ORM\EntityManagerInterface;


class Calculator{
    public function __construct(EntityManagerInterface $doctrine){
        $this->doctrine=$doctrine;
    }
    public function add($a, $b){return $a+$b;}
    public function substruct($a, $b){return $a-$b;}
    public function multiply($a, $b){return $a*$b;}
    public function divide($a, $b){
        if ($b === 0){
            return false;
        }
        return $a/$b;
    }

    public function calculate(\App\Entity\Calculator $calculator){
        $a= $calculator->getA();
        $b= $calculator->getB();
        $operator= $calculator->getOperator();

        switch ($operator){
            case '+':
                return $this->add($a, $b);
                break;
            case '*':
                return $this->multiply($a, $b);
                break;
            case '-':
                return $this->substruct($a, $b);
                break;
            case '/':
                return $this->divide($a, $b);
                break;
            default:
                return $this->add($a, $b);
        }
    }
    public function purge(){
        $sql="delete from calculator";
        $conn=$this->doctrine->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
    }
    public function fetchOperations()
    {
        return $this
            ->doctrine
            ->getRepository(\App\Entity\Calculator::class)
            ->findAll();
    }

    public function saveCalculator($calculator, $result)
    {
        $calculator->setResult($result);
        $this->doctrine->persist($calculator);
        $this->doctrine->flush();
    }
}