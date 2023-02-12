<?php

$ilio_dir = "ILIO";

// Vérification de l'existence du dossier principal
if (!is_dir($ilio_dir)) {
    die("Le dossier principal n'existe pas.");
}

// Parcours de chaque dossier utilisateur
foreach (new DirectoryIterator($ilio_dir) as $user) {
    if ($user->isDot() || !$user->isDir()) {
        continue;
    }
    $user_dir = $user->getPathname();

    // Vérification de l'existence du dossier AGENDA
    $agenda_dir = $user_dir . "/AGENDA";
    if (!is_dir($agenda_dir)) {
        continue;
    }

    // Parcours de chaque année
    foreach (new DirectoryIterator($agenda_dir) as $year) {
        if ($year->isDot() || !$year->isDir()) {
            continue;
        }
        $year_dir = $year->getPathname();

        // Parcours de chaque mois
        foreach (new DirectoryIterator($year_dir) as $month) {
            if ($month->isDot() || !$month->isDir()) {
                continue;
            }
            $month_dir = $month->getPathname();
            $month_name = $month->getFilename();

            // Parcours de chaque jour
            foreach (new DirectoryIterator($month_dir) as $day) {
                if ($day->isDot() || !$day->isFile()) {
                    continue;
                }
                $day_file = $day->getPathname();
                $day_date = str_replace(".day", "", $day->getFilename());

                // Lecture du fichier du jour
                $day_content = file_get_contents($day_file);
                $events = explode("\n", $day_content);

                // Affichage des événements du jour
                echo "<h1>Agenda de $user pour le $day_date</h1>";
                echo "<ul>";
                foreach ($events as $event) {
                    if (empty($event)) {
                        continue;
                    }
        // On récupère le contenu des fichiers .day du jour en cours
        $dayFilePath = $yearPath . '/' . $monthName . '/' . $day . '/' . $day . '.day';
        if (file_exists($dayFilePath)) {
            $dayFile = fopen($dayFilePath, 'r');
            $dayContent = fread($dayFile, filesize($dayFilePath));
            fclose($dayFile);
            $dayEvents = explode("\n", $dayContent);
        } else {
            $dayEvents = array();
        }

        // On récupère le contenu des fichiers d'objectifs en cours
        $todoFilePath = $userPath . '/TODO/liste' . $currentList . '/' . $currentDate . '.todo';
        if (file_exists($todoFilePath)) {
            $todoFile = fopen($todoFilePath, 'r');
            $todoContent = fread($todoFile, filesize($todoFilePath));
            fclose($todoFile);
            $todoData = explode("\n", $todoContent);
            $todoName = $todoData[0];
            $todoDesc = $todoData[1];
            $todoStart = $todoData[2];
            $todoEnd = $todoData[3];
        } else {
            $todoName = '';
            $todoDesc = '';
            $todoStart = '';
            $todoEnd = '';
        }

        // On affiche le contenu de la page
        echo '<h2>' . $currentDate . '</h2>';
        echo '<h3>Agenda</h3>';
        foreach ($dayEvents as $event) {
            $eventData = explode('|', $event);
            if (count($eventData) == 3) {
                $startTime = $eventData[0];
                $endTime = $eventData[1];
                $eventName = $eventData[2];
                echo '<p>' . $startTime . ' - ' . $endTime . ' : ' . $eventName . '</p>';
            }
        }
        echo '<h3>Objectifs</h3>';
        echo '<h4>' . $todoName . '</h4>';
        echo '<p>' . $todoDesc . '</p>';
        echo '<p>Du ' . $todoStart . ' au ' . $todoEnd . '</p>';
    } else {
        // Si la date n'est pas valide, on affiche un message d'erreur
        echo '<p>Date invalide</p>';
    }
}

