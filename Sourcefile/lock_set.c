/* 使用多个终端同时执行读取锁或写入锁操作 */
int lock_set(int fd, int type)
{
	struct flock old_lock,lock;
	//加锁整个文件
	lock.l_whence = SEEK_SET;	/* 当前位置为文件开头 */
	lock.l_start = 0;	/* 相对位移量为0 */
	lock.l_len = 0;		/* 加锁区域为0 */
	
	lock.l_type = type;	/* 锁的类型由传递参数确定 */
	lock.l_pid = -1;

	fcntl(fd, F_GETLK, &lock);	/* F_GETLK 根据lock参数值确定是否上文件锁 */

	if (lock.l_type != F_UNLCK)	//判断是否已上锁
	{
		if (lock.l_type == F_RDLCK)	/* 判断读取锁 */
		{
			printf("Read lock already set by %d\n",lock.l_pid);
		}
		else if (lock.l_type == F_WRLCK)	/* 判断写入锁 */
		{
			printf("Write lock already set by %d\n",lock.l_pid);
		}
	}

	lock.l_type = type;	/* F_GETLK 可能会修改锁的类型 */

	if ((fcntl(fd, F_SETLKW, &lock)) < 0)	/* 根据不同的锁类型来进行阻塞式上锁或解锁 */
	{
		printf("Lock failed:type = %d\n",lock.l_type);
		return 1;
	}

	switch (lock.l_type)
	{
		case F_RDLCK:
			{
				printf("Read lock set by %d\n",getpid());
			}
			break;
		case F_WRLCK:
			{
				printf("Write lock set by %d\n",getpid());
			}
			break;
		case F_UNLCK:
			{
				printf("Realease lock by %d\n",getpid());
				return 1;
			}
			break;

		default:
			break;
	}
	return 0;
}
