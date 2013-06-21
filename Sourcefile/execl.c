#include <unistd.h>
#include <stdio.h>
#include <stdlib.h>

int main()
{
	if (fork() == 0)
	{
		if (execl("/bin/ps", "ps", "-aux", NULL) < 0)
		{
			printf("Execl error\n");
		}
	}
}
