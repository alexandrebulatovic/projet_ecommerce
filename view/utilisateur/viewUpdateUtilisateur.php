<form style="margin:40px;" method="post" action="index.php?controller=utilisateur&action=updated">
  <fieldset>
    <legend>Modification de compte</legend>
	<p>
	  <label>Changer l'e-mail</label> :
	  <input type="email" placeholder="Ex : jeanneymar@gmail.com" name="email" />
	</p>
	<?php
		if (Session::isAdmin())
			echo "<p><label>Administrateur ?</label>
			<input type='checkbox' value='1' name='admin' checked/></p>";
	?>
	<p>
      <label>Nom</label> :
      <input type="text" placeholder="Ex : Jean" name="nom" value="<?php echo $nom;?>" required />
    </p>
    <p>
      <label>Prenom</label> :
      <input type="text" placeholder="Ex : Neymar" name="prenom" value="<?php echo $prenom;?>" required />
    </p>
	<p>
      <label>Changer le mot de passe</label> :
      <input type="password" placeholder="Ex : 89aEG3F22LCbwjZV" name="mdp" />
    </p>
	<p>
      <label>Confirmer le nouveau mot de passe</label> :
      <input type="password" placeholder="Ex : 89aEG3F22LCbwjZV" name="mdpC" />
    </p>
    <p>
      <input type="submit" value="Envoyer" />
    </p>
  </fieldset> 
</form>