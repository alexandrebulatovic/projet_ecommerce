<form style="margin:40px;" method="post" action="index.php?action=loggedin&controller=utilisateur">
	<fieldset>
		<legend>Connexion</legend>
			<p>
				<label>E-mail</label> :
				<input type="email" placeholder="Ex : jeanneymar@gmail.com" name="email" required />
			</p>
			<p>
				<label>Mot de passe</label> :
				<input type="password" placeholder="Ex : 89aEG3F22LCbwjZV" name="mdp" required />
			</p>
				<a href="index.php?controller=utilisateur&action=create">Pas encore inscrit ?</a>
			<p>
				<input type="submit" value="Envoyer" />
			</p>
	</fieldset> 
</form>
