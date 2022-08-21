<!--api.php-->
<form method="POST">
    <input type="submit" formaction="data/totalcrimestats.php" style="visibility: hidden; display: none;">
    <lable for="api_key">Enter your API Key</lable>
    <input type="text" value="" id="api_key" name="api_key" /></br>
    <p id="sub-text">Then click on the button below where you would like to go</p>
    <button formaction="data/theft_and_handling_stolen_goods.php">Theft and Handling Stolen Goods</button>
    <input type="submit" formaction="data/totalcrimestats.php" value="Total Crime Data" class="submit-1" />
</form>