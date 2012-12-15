#include <stdio.h> 
#include <string.h> 
#include <netdb.h> 
#include <sys/types.h>
#include <netinet/in.h> 
#include <sys/socket.h> 
int main(int argc, char *argv[])
{
	int socket_fd, new_fd;
	int port, sin_size;
	struct sockaddr_in server_addr;
	struct sockaddr_in client_addr;
	if (argc < 2) {
		fprintf(stderr, "need a port");
		exit(1);
	}
	if ((port = atoi(argv[1])) < 0) {
		fprintf(stderr, "invalid port");
		exit(1);
	}
	

	socket_fd = socket(AF_INET, SOCK_STREAM, 0);
	server_addr.sin_family=AF_INET; 
	server_addr.sin_addr.s_addr=htonl(INADDR_ANY); 
	server_addr.sin_port=htons(port);
	bind(socket_fd, (struct sockaddr *)&server_addr, sizeof(struct sockaddr));
	listen(socket_fd, 5);
	while(1) {
		sin_size = sizeof(struct sockaddr_in);
		new_fd = accept(socket_fd, (struct sockaddr *)&client_addr, &sin_size);
		inet_ntoa(client_addr.sin_addr);
		write(new_fd, "hello, this is from server", strlen("hello, this is from server"));
		sleep(3);
		close(new_fd);
	}
}
