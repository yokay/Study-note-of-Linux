##如何创建本仓库的  

由于每次创建时，都忘记如何同步仓库，所以将步骤写下来，防止自己进一步失忆。  


在[github](https://www.github.com)上 `Create a new repo` 后，我直接 `clone` 到了桌面，然后将文件添加到各个文件夹中。由于没有初始化，出现很多问题。所以做了以下事情：  

	ssh-keygen -t rsa -C "xxx(github账号)"
	登陆github账户，点击 ***Accout Settings > SSH Public Keys >Add SSH key **，然后将 ` ~/.ssh/id_ras.pub ` 中的SSH key复制进去。
	ssh -T git@github.com 测试是否连接到github服务器上。
	git config --global user.name 'xxx'
	git config --global user.email xxx@xxx.com
	进入仓库目录，git init
	git remote add origin git@github.com:xxx/xxx.git
	git push -u origin master 

至此，push成功！
