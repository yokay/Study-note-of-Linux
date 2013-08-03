##如何创建本仓库的  

由于每次创建时，都忘记如何同步仓库，所以将步骤写下来，防止自己进一步失忆。  


在[github](https://www.github.com)上 `Create a new repo` 后，我直接 `clone` 到了桌面，然后将文件添加到各个文件夹中。由于没有初始化，出现很多问题。所以做了以下事情：  

	1. ssh-keygen -t rsa -C "xxx(github账号)"
	2. 登陆github账户，点击 ***Accout Settings > SSH Public Keys >Add SSH key **，
	   然后将 ` ~/.ssh/id_ras.pub ` 中的SSH key复制进去。
	3. ssh -T git@github.com 测试是否连接到github服务器上。
	4. git config --global user.name 'xxx'
	5. git config --global user.email xxx@xxx.com
	6. 进入仓库目录，git init
	7. git remote add origin git@github.com:xxx/xxx.git
	8. git push -u origin master 

至此，push成功！

一般来说从github上从终端或者直接clone下来的库，经过以上8个过程基本上就可以建成功。如果提示![rejected]	master -> master (non-fast-forward)的话，我使用了一个比较笨的方法，就是删掉电脑上的库，重新clone下来，然后直接就可以用了。  

之后又建立了一个新库，若按上面步骤ssh-key会被覆盖。直接进入第7步，出现错误:  
` fatal:remote origin already exists.`    
解决办法:  
` git remote rm origin `  
再执行第7步  
接着  
` git push origin master `  
若出现错误:  
` error:failed to push som refs to .... `  
` git pull origin master `  


这两天使用github时发现图片老是无法显示，一开始使用的链接是图片所在的文件的路径，后来发现打开图片后显示的源文件路径才是正确的。其查看方法如下图所示： 

**打开图片后点击RAW**  

![打开图片后点击RAW](https://raw.github.com/yokay/Images/master/github-showpic01.png "打开图片后点击RAW")  

**复制地址栏**  

![复制地址栏](https://raw.github.com/yokay/Images/master/github-showpic02.png "复制地址栏")  

P.S:不是github.com开头，而是raw！  
