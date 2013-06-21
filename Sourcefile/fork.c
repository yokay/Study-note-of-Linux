#include <sys/types.h>
#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>

int main(void)
{
	pid_t result;

	result = fork();

	if(result == -1)
	{
		printf("Fork error\n");
	}
	else if (result == 0)
	{
		printf("The returned value is %d\nIn child-process!!\nMy PID is %d\n",result,getpid());
	}
	else
	{
		printf("The returnen value is %d\nIn father-process!!\nMy PID is %d\n",result,getpid());
		return result;
	}
}
