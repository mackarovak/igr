<?php
session_start();

$page_title = "Наши сотрудники";
$site_title = "Салон красоты - Наши сотрудники";

ob_start();

$body_content = "
<div class='container'>
    <div class='product'>
        <img src='staff1.png' alt='Сотрудник 1'>
        <h3>Анастасия</h3>
        <p>Стилист-колорист</p>
    </div>
    <div class='product'>
        <img src='staff2.jpg' alt='Сотрудник 2'>
        <h3>Валерия</h3>
        <p>Мастер эпиляции</p>
    </div>
    <div class='product'>
        <img src='staff3.jpg' alt='Сотрудник 3'>
        <h3>Евгения</h3>
        <p>Мастер маникюра</p>
    </div>
</div>";

$footer_content = "© 2024 Салон красоты";
include 'base.html';
?>