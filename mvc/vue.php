<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Stream : Utilisateurs</title>

    <!-- Scripts Semantic-UI et jQuery -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.js"></script>
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="css/customer.css">
</head>

<body>

    <div class="ui grid container">

        <div class="row">
            <div class="column">
                <h2 class="ui dividing middle centered header" style="margin: 3rem 0 0 0 ">Tableau des utilisateurs</h2>
            </div>
        </div>

        <div class="row">
            <div class="column">
                <table class="ui striped center aligned celled selectable table">
                    <thead>
                        <tr>
                            <?php
                            $html = '';
                            
                            for($i = 0; $i < count($colnames); $i++){
                                $sort = $sortBy == 'asc' ? 'desc' : 'asc';
                                $html .= '<th><a href="?page=' . $nbPage . '&sortName=' . $colnames[$i] . '&sortBy=' . $sort . '">' . $colnames[$i] . '</a></th>';

                            }
                            echo $html;

                            ?>
                            <th>Modifier</th>
                            <th>Locations</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php

                        $html = '';

                        foreach ($rows as $row) {
                            $html .= '<tr>';
                            for ($i = 0; $i < count($colnames); $i++) {
                                $html .= '<td>' . $row[$i] . '</td>';
                            }
                            $html .= '<td style="text-align:center;"><i data-id="' . $row[0] . '" class="updateUser edit outline icon ajax"></i></td>';
                            $html .= '<td style="text-align:center;"><i data-id="' . $row[0] . '" class="viewLoc eye icon ajax"></i></td>';

                            $html .= '</tr>';
                        }

                        echo $html;

                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="three column stackable row">
            <div class="three wide column"></div>

            <div class="ten wide column center aligned">
                <div class="ui pagination menu">
                    
                    <?php // echo $table->get_previous_links($nbPage); ?>
                    <?php echo $table->print_links_for_current_page($nbPage); ?>
                    <?php // echo $table->get_next_links($nbPage); ?>
                    
                </div>
            </div>

            <div class="three wide column right aligned">
                <button class="ui right labeled icon basic button"><i class="plus icon"></i>Ajouter</button>
            </div>

        </div>

    </div>

    <!-- Modal pour modifier les utilisateurs -->

    <div class="ui coupled modal" id="updateUser">
        <i class="close icon"></i>
        <div class="header">
            Informations utilisateur
        </div>
        <div class="content">
            <!-- résultat de customer_info.php via notre requête Ajax -->
        </div>
        <div class="actions">
            <div class="ui close button">Annuler</div>
            <button class="ui primary right labeled icon submit button" id="submitUpdateUser">
                <i class="right arrow icon"></i>
                Modifier
            </button>
        </div>
    </div>

    <!-- Modal confirmation modification utilisateur -->

    <div class="ui coupled mini modal" id="confirmUpdateUser">
        <div class="header">Modification utilisateur</div>
        <div class="content">
            <p>Etes vous sûr de vouloir modifier cet utilisateur ?</p>
        </div>
        <div class="actions">
            <div class="ui negative cancel button">Annuler</div>
            <div class="ui positive approve button">Confirmer</div>
        </div>
    </div>

    <!-- Modal pour voir les locations de chaque utilisateur -->

    <div class="ui modal" id="viewLoc">
        <i class="close icon"></i>
        <div class="header">
            Locations utilisateur
        </div>
        <div class="scrolling content">
            <!-- Liste des emprunts inséré grâce à Ajax -->
        </div>
        <div class="actions">
            <div class="ui close button">Fermer</div>
        </div>
    </div>


</body>

</html>