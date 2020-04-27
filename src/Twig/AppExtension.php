<?php


namespace App\Twig;


use App\Entity\Project;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('format_project_status', [$this, 'formatProjectStatus'])
        ];
    }

    public function formatProjectStatus(string $status)
    {
        if ($status === Project::STATUS_NEW) {
            return 'Nouveau';
        }
        if ($status === Project::STATUS_LAUNCHED) {
            return 'En cours';
        }
        if ($status === Project::STATUS_DONE) {
            return 'Terminé';
        }
    }
}