<style>
  .catalog {
    display: flex;
    flex-direction: row;
    justify-content: space-evenly;
    padding: 100px;
    width: 100%;
  }

  .sidebar ul {
    min-width: 150px;
    display: flex;
    flex-direction: column;
    list-style: none;
    border-radius: 6px;
    background-color: white;
    padding: 0px;
    border: 1px solid black;
  }

  .sidebar li {
    padding: 10px;
    border-bottom: 1px solid #e6e6e6;
  }

  .sidebar li a {
    text-decoration: none;
    color: black;
  }

  .catalog p {
    margin: 0px;
  }
</style>

<div class="sidebar">
  <ul>
    <li><a href="account.php">Account</a></li>
    <li><a href="wishlist.php">Wishlist</a></li>
    <li><a href="#">Order History</a></li>
    <li><a href="refill-webcoins.php">Refill Webcoins</a></li>
  </ul>
</div>