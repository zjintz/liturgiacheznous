<?php

namespace App\Util;

use App\Application\Sonata\UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

/**
 * \brief     Auxiliar functions to the commands that send mails. 
 *
 *
 */
class MailerAssistant
{
    private $entityManager;
    
    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getEnabledUsers()
    {
        $userRepo = $this->entityManager->getRepository(User::class);
        $enabledUsers = $userRepo->findBy(
             ['enabled'=>true]
        );
        return $enabledUsers;
    }
    
    /**
     * Get the users that have an actived email subscription.
     *
     *
     */
    public function getSubscribedUsers($enabledUsers)
    {
        $subscribedUsers = [];
        foreach($enabledUsers as $user){
            $subsc = $user->getEmailSubscription();
            if (!is_null($subsc)) {
                if ($subsc->getIsActive()) {
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

    
}
