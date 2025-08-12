<?php
function covertion($all_unite = [], $min_qte = 0)
{
    $by_unite = null;
    $reste = 0;
    // ajouter la valeur pour l'unité la plus petite


    $data = [];
    if (count($all_unite) > 0) {
        $data = [[
            'unite' => end($all_unite)->denomination,
            'quantite' => $min_qte,
            'reste' => $reste
        ]];
    } else {
        $data = [[
            'unite' => '',
            'quantite' => $min_qte,
            'reste' => $reste
        ]];
    }
    for ($i = count($all_unite) - 2; $i >= 0; $i--) {
        if (isset($all_unite[$i + 1])) {
            $element = $all_unite[$i];
            $unite = $element->denomination;

            // vérification si il y a un reste
            $reste = $min_qte % $all_unite[$i + 1]->formule;
            if ($reste != 0) {
                if ($reste > 1) {
                    $reste = $reste . ' ' . $all_unite[$i + 1]->denomination . '(s)';
                } else {
                    $reste = $reste . ' ' . $all_unite[$i + 1]->denomination;
                }
            }

            $min_qte = intval($min_qte / $all_unite[$i + 1]->formule);

            $by_unite = [
                'unite' => $unite,
                'quantite' => $min_qte,
                'reste' => $reste
            ];

            array_unshift($data, $by_unite);
        }
    }
    return $data;
}



function stock_texte($unite = [], $id = 0)
{
    $texte = '';
    $concat = '+';

    for ($i = $id; $i < count($unite); $i++) {
        $element = $unite[$i];

        if ($i == $id) {
            // on affiche rien si la quantité est 0 
            if ($element["quantite"] > 0) {
                $texte = $element["quantite"] . ' ' . $element["unite"];
                if ($element["quantite"] > 1) {
                    // pour mettre le s
                    $texte = $element["quantite"] . ' ' . $element["unite"] . '(s)';
                }
                // sans unite 
                if (empty($element["unite"])) {
                    $texte = $element["quantite"];
                }
            }
        }

        if ($element["reste"] != 0) {
            // reste
            if ($element["quantite"] > 0) {
                $texte .= $concat . ' ' . $element["reste"];
            } else {
                $texte .= $element["reste"];
            }
        }
    }
    return $texte;
}
