<?php

namespace App\Util;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

/**
 * \brief     Auxiliar functions to the commands that send mails.
 *
 *
 */
class MailerAssistant
{
    private $entityManager;
    private $parameterBag;
    private $cnbbAssembler;
    private $santaInesAssembler;
    
    public function __construct(
        EntityManagerInterface $entityManager,
        ParameterBagInterface $parameterBag,
        CNBBAssembler $cnbbAssembler,
        IgrejaSantaInesAssembler $santaInesAssembler
    ) {
        $this->parameterBag= $parameterBag;
        $this->entityManager = $entityManager;
        $this->cnbbAssembler = $cnbbAssembler;
        $this->santaInesAssembler = $santaInesAssembler;
    }

    public function getEnabledUsers()
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $enabledUsers = $userRepo->findBy(['enabled'=>true]);
        return $enabledUsers;
    }
    
    /**
     * Get the users that have an actived email subscription.
     *
     *
     */
    public function getSubscribedUsers(
        $enabledUsers,
        string $period = "daily",
        int $daysAhead = 0
    ) {
        $subscribedUsers = [];
        $checkDaysAhead = ($daysAhead === 1 ) ||
                        ($daysAhead === 2 ) ||
                        ($daysAhead === 3 );
        foreach ($enabledUsers as $user) {
            $subsc = $user->getEmailSubscription();
            if (!is_null($subsc)) {
                $isActive =$subsc->getIsActive();
                $isThePeriod = $this->checkPeriod(
                    $subsc->getPeriodicity(),
                    $period
                );
                $sameDaysAhead = ($daysAhead === $subsc->getDaysAhead());

                if (!$checkDaysAhead) {
                    $sameDaysAhead = true;
                }
                if ($isActive && $isThePeriod && $sameDaysAhead) {
                    $subscribedUsers[] = $user;
                }
            }
        }
        return $subscribedUsers;
    }
    
    public function listFilesToMake($days, $startDate)
    {
        $filesToSend = [];
        // remember that ther are days ahead: 1 , 2 and 3, therefore this
        //        for ends in days+2.
        for ($i=1; $i <= ($days+2); $i++) {
            $newDate = clone $startDate;
            $newDate->add(
                new \DateInterval(
                    'P'.($i).'D'
                )
            );
            $dateString = $newDate->format('Y-m-d');

            $filesToSend[] = ["file_name" => "doc-CNBB_".$dateString.".DOCX",
                              "date_string" => $dateString,
                              "source"=>"CNBB",
                              "format"=>"DOCX"
            ];
            $filesToSend[] = ["file_name" => "doc-CNBB_".$dateString.".PDF",
                              "date_string" => $dateString,
                              "source"=>"CNBB",
                              "format"=>"PDF"
            ];
            $filesToSend[] = ["file_name" =>"doc-Igreja_Santa_Ines_".$dateString.".DOCX",
                              "date_string" => $dateString,
                              "source"=>"Igreja_Santa_Ines",
                              "format"=>"DOCX"
            ];
            $filesToSend[] = ["file_name" => "doc-Igreja_Santa_Ines_".$dateString.".PDF",
                              "date_string" => $dateString,
                              "source"=>"Igreja_Santa_Ines",
                              "format"=>"PDF"
            ];
        }

        return $filesToSend;
    }

    private function checkPeriod($subsPeriod, $period)
    {
        $isDaily = $subsPeriod === "1" && $period === "daily";
        if ($isDaily) {
            return true;
        }
        $isWeekly = $subsPeriod === "7" && $period === "weekly";
        if ($isWeekly) {
            return true;
        }
        $isBiweekly = $subsPeriod === "14" && $period === "biweekly";
        if ($isBiweekly) {
            return true;
        }
            
        return false;
    }

    
    public function makeLiturgyText($toMake)
    {
        $rootDir = $this->parameterBag->get('kernel.project_dir');
        $dateString = $toMake["date_string"];
        $filePath = $rootDir.'/data/liturgy_texts/'.$toMake["file_name"];
        if(file_exists($filePath)){
            return '>>> '.$toMake["file_name"].' is already there.';
        }
        $assembler = $this->getAssembler($toMake["source"]);
        $docFile = $assembler->getDocument($dateString, $toMake["format"]);
        if ($docFile == "Not_Found") {
            return 'WARNING : Not found.';
        }
        rename($docFile, $filePath);
        return "Done.";
    }

    private function getAssembler($source)
    {
        if($source === "CNBB")
            return $this->cnbbAssembler;
        return $this->santaInesAssembler;
    }
}
