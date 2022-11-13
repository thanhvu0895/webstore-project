<?php
$displayNone = (!isset($_SESSION["email"]))  ? "style='display:none'" : '';
?>

<style>
    .menu {
        width: 100%;
        height: 50px;
        background-color: #ffd580;
        display: flex;
        align-items: center;
        flex-direction: row;
        overflow: hidden;
        justify-content: center;
    }

    .menu p {
        margin-left: 5px;
        margin-right: 5px;
        margin-bottom: 0px;
        padding: 3px;
        font-size: 15px;
    }

    .menu form :hover {
        cursor: pointer;
        border: 1.5px solid rgb(253, 240, 213);
        border-radius: 5px;
    }

    @media screen and (max-width: 720px) {
        .menu {
            height: 36px;
        }

        .menu p {
            font-size: 12px;
        }
    }
</style>

<div class="menu">
    <form id="formFashion" action="index.php?category=Fashion" method="post">
        <p onclick="document.getElementById('formFashion').submit();">Fashion</p>
    </form>

    <form id="formElectronic" action="index.php?category=Electronics" method="post">
        <p onclick="document.getElementById('formElectronic').submit();">Electronics</p>
    </form>

    <form id="formTumblers" action="index.php?category=Tumblers" method="post">
        <p onclick="document.getElementById('formTumblers').submit();">Tumblers</p>
    </form>

    <form id="formBags" action="index.php?category=Bags" method="post">
        <p onclick="document.getElementById('formBags').submit();">Bags</p>
    </form>

</div>