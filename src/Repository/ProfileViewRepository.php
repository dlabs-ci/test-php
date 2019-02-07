<?php

namespace BOF\Repository;

use BOF\Entity\ProfileView;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method ProfileView|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileView|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileView[]    findAll()
 * @method ProfileView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileViewRepository extends ServiceEntityRepository
{

    private $validator;
    private $query;
    private $errors = [];
    const QUERY_YEARS = 1;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProfileView::class);
        $this->validator = Validation::createValidator();
        $this->query = $this->createQueryBuilder('t');
    }

    /**
     * @param $arguments
     * @return ProfileView[] Returns an array of ProfileView objects
     */
    public function search(array $arguments)
    {
        foreach ($arguments as $key => $param) {
            if (self::QUERY_YEARS == $key) {
                $this->addYearQuery($param);
            }
        }
        return $this->query->getQuery()->getResult();
    }

    public function validate($arguments)
    {
        $this->errors = [];
        foreach ($arguments as $key => $param) {
            if (self::QUERY_YEARS == $key) {
                $this->validateYear($param);
            }
        }
        return count($this->errors) ? false : true;
    }

    public function getErrors()
    {
        return $this->errors;
    }

//    public function findByYear($value)
//    {
//        $start = $value . '-01-01';
//        $end = $value . '-12-12';
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.date BETWEEN :startDate and :endDate')
//            ->setParameter('startDate', $start)
//            ->setParameter('endDate', $end)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    private function addYearQuery($param)
    {
        $start = $param . '-01-01';
        $end = $param . '-12-12';
        $this->query->andWhere('t.date BETWEEN :startDate and :endDate')
            ->setParameter('startDate', $start)
            ->setParameter('endDate', $end);
    }


    private function validateYear($val)
    {
        $currentYear =  date("Y");
        $violations = $this->validator->validate($val, [
            new Range(['min' => 2000, 'max' => $currentYear]),
            new NotBlank(),
        ]);
        if ($violations) {
            /** @var ConstraintViolation $error */
            foreach ($violations as $error) {
                $this->errors []= $error->getMessage();
            }
        }
    }
}
