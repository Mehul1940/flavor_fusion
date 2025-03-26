<?php

$PROJECT_NAME = "/flavor_fusion";

define("ASSETS_PATH", "$PROJECT_NAME/assets/");
define("ROOT", "$PROJECT_NAME/");
define("DB_ROOT", $_SERVER['DOCUMENT_ROOT'] . "$PROJECT_NAME/database/");

function displayStars($rating)
{
    $stars = "";

    for ($i = 1; $i <= 5; $i++) {
        if ($i <= floor($rating)) {
            $stars .= '<i class="fa-solid fa-star text-warning"></i> ';
        } elseif ($i - 0.5 <= $rating) {
            $stars .= '<i class="fa-solid fa-star-half-stroke text-warning"></i> ';
        } else {
            $stars .= '<i class="fa-regular fa-star text-secondary"></i> ';
        }
    }

    return $stars;
}

function getAverageRating($reviews)
{
    if (empty($reviews)) return 0;
    $totalRating = array_sum(array_column($reviews, "rating"));
    $reviewCount = count($reviews);
    $averageRating = $totalRating / $reviewCount;
    return round($averageRating, 1);
}



function parse_input($input)
{
    $parsed = trim($input);
    $parsed = stripslashes($parsed);
    $parsed = htmlspecialchars($parsed, ENT_QUOTES, "UTF-8");
    return $parsed;
}


function redirect($location)
{
    header('Location:' .  ROOT . $location, true, 301);
}

function show_alert($message)
{
    echo '<div class="position-fixed start-50 translate-middle z-3 toast align-items-center show" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ' . htmlspecialchars($message) . '
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
          </div>';
}


// AUTH

session_start();

function login($user)
{
    $_SESSION["user"] = $user;
}

function logout()
{
    $_SESSION["user"] = null;
}

function get_auth_user()
{
    if (isset($_SESSION["user"])) {
        return $_SESSION["user"];
    }
    return null;
}

function enable_unprotected_route()
{
    $user = get_auth_user();

    if ($user !== null) {
        header("Location: " . ROOT);
    }
}

function enable_protected_route()
{
    $user = get_auth_user();

    if ($user === null) {
        header("Location: " . ROOT . 'login');
    }
}

function enable_admin_route()
{
    $user = get_auth_user();

    if ($user === null || $_SESSION["user"]["role"] != "admin") {
        header("Location: " . ROOT . 'admin/login');
    }
}


// EMAIL

function sendEmail($to, $subject, $message, $fromEmail = 'noreply@example.com', $fromName = 'Your Company')
{
    $headers = "From: $fromName <$fromEmail>\r\n";
    $headers .= "Reply-To: $fromEmail\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        return "Email sent successfully!";
    } else {
        return "Failed to send email.";
    }
}
