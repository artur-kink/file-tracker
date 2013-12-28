#include <iostream>
#include <stdio.h>
#include <stdlib.h>
#include <cstdlib>

#include <vector>
#include <string>


#include "boost/filesystem.hpp"
#include "boost/asio.hpp"

namespace ip = boost::asio::ip;
namespace fs = boost::filesystem;

std::vector<std::string> extensions;
std::vector<std::string> paths;

char client_name[25];
int id;
char authkey[255];

void register_client(){
    boost::asio::io_service io_service;

    // Get a list of endpoints corresponding to the server name.
    ip::tcp::resolver resolver(io_service);
    ip::tcp::resolver::query query("127.0.0.1", "http");
    ip::tcp::resolver::iterator endpoint_iterator = resolver.resolve(query);
    ip::tcp::resolver::iterator end;

    // Try each endpoint until we successfully establish a connection.
    ip::tcp::socket socket(io_service);
    boost::system::error_code error = boost::asio::error::host_not_found;
    while (error && endpoint_iterator != end){
      socket.close();
      socket.connect(*endpoint_iterator++, error);
    }
    if (error)
      throw boost::system::system_error(error);

    // Form the request. We specify the "Connection: close" header so that the
    // server will close the socket after transmitting the response. This will
    // allow us to treat all data up until the EOF as the content.
    boost::asio::streambuf request;
    std::ostream request_stream(&request);
    request_stream << "GET " << "/register.php?name=" << client_name << " HTTP/1.0\r\n";
    request_stream << "Host: " << "127.0.0.1" << "\r\n";
    request_stream << "Accept: */*\r\n";
    request_stream << "Connection: close\r\n\r\n";

    // Send the request.
    boost::asio::write(socket, request);
    
    // Read the response status line.
    boost::asio::streambuf response;
    boost::asio::read_until(socket, response, "\r\n");
    
    // Check that response is OK.
    std::istream response_stream(&response);
    std::string http_version;
    response_stream >> http_version;
    unsigned int status_code;
    response_stream >> status_code;
    std::string status_message;
    std::getline(response_stream, status_message);

    if(!response_stream || http_version.substr(0, 5) != "HTTP/"){
      std::cout << "Invalid response\n";
      return;
    }

    if(status_code != 200){
      std::cout << "Response returned with status code " << status_code << "\n";
      return;
    }

    // Read the response headers, which are terminated by a blank line.
    boost::asio::read_until(socket, response, "\r\n\r\n");

    // Process the response headers.
    std::string header;
    while (std::getline(response_stream, header) && header != "\r")
        std::cout << header << "\n";

    std::string line;
    std::getline(response_stream, line);
    id = std::atoi(line.c_str());
    std::cout << "Client Id: " << id << std::endl;
    std::getline(response_stream, line);
    std::strncpy(authkey, line.c_str(), 255);
    std::cout << "Auth Key: " << authkey << std::endl;
    std::getline(response_stream, line);

    char * pch;
    pch = strtok((char*)line.c_str(), ";");
    while (pch != NULL){
      paths.push_back(std::string(pch));
      pch = strtok(NULL, ";");
    }

    std::getline(response_stream, line);
    pch = strtok((char*)line.c_str(), ";");
    while (pch != NULL){
      extensions.push_back(std::string(pch));
      pch = strtok(NULL, ";");
    }

    // Write whatever content we already have to output.
    if (response.size() > 0)
      std::cout << &response;

    // Read until EOF, writing data to output as we go.
    while (boost::asio::read(socket, response,
          boost::asio::transfer_at_least(1), error))
      std::cout << &response;
    if (error != boost::asio::error::eof)
      throw boost::system::system_error(error);
}

std::vector<fs::path> files;
std::string file_data;

void directory_recurse(fs::path& dir){
    if(fs::exists(dir) && fs::is_directory(dir)){
        fs::directory_iterator end;
        bool posted_path = false;
        for(fs::directory_iterator it(dir); it != end; ++it){
            if(fs::is_regular_file(*it)){
                bool valid_ext = extensions.size() == 0;
                for(std::vector<std::string>::iterator ext = extensions.begin(); ext != extensions.end(); ++ext){
                    if((*ext) == (*it).path().extension()){
                        valid_ext = true;
                        break;
                    }
                }
                if(valid_ext){
                    if(!posted_path){
                        posted_path = true;
                        file_data.append(dir.string().c_str());
                        file_data.append("\n");
                    }
                    file_data.append((*it).path().filename().c_str());
                    file_data.append(";");
                    char size[25];
                    sprintf(size, "%d", (unsigned int)fs::file_size((*it).path()));
                    file_data.append(size);
                    file_data.append(";");
                    std::time_t modified_date = fs::last_write_time((*it).path());
                    char time[25];
                    sprintf(time, "%d", modified_date);
                    file_data.append(time);
                    file_data.append("\n");
                    files.push_back((*it).path());
                }
            }else if(fs::is_directory(*it) && !fs::is_symlink(*it)){
                fs::path rdir = (*it).path();
                directory_recurse(rdir);
            }
        }
    }
}

void post_files(){
    boost::asio::io_service io_service;

    // Get a list of endpoints corresponding to the server name.
    ip::tcp::resolver resolver(io_service);
    ip::tcp::resolver::query query("127.0.0.1", "http");
    ip::tcp::resolver::iterator endpoint_iterator = resolver.resolve(query);
    ip::tcp::resolver::iterator end;

    // Try each endpoint until we successfully establish a connection.
    ip::tcp::socket socket(io_service);
    boost::system::error_code error = boost::asio::error::host_not_found;
    while (error && endpoint_iterator != end){
      socket.close();
      socket.connect(*endpoint_iterator++, error);
    }
    if (error)
      throw boost::system::system_error(error);

    // Form the request. We specify the "Connection: close" header so that the
    // server will close the socket after transmitting the response. This will
    // allow us to treat all data up until the EOF as the content.
    boost::asio::streambuf request;
    std::ostream request_stream(&request);
    request_stream << "POST " << "/post_files.php" << " HTTP/1.1\r\n";
    request_stream << "Host: " << "127.0.0.1" << "\r\n";
    request_stream << "Accept: */*\r\n";
    request_stream << "Connection: close\r\n";
    request_stream << "Content-Type: application/x-www-form-urlencoded\r\n";
    request_stream << "Content-Length: " << (19 + strlen(authkey) + file_data.length()) << "\r\n\r\n";
    request_stream << "id=" << id << "&auth_key=" << authkey << "&body=" << file_data;
    std::cout << file_data;
    // Send the request.
    boost::asio::write(socket, request);

    // Read the response status line.
    boost::asio::streambuf response;
    boost::asio::read_until(socket, response, "\r\n");

    // Check that response is OK.
    std::istream response_stream(&response);
    std::string http_version;
    response_stream >> http_version;
    unsigned int status_code;
    response_stream >> status_code;
    std::string status_message;
    std::getline(response_stream, status_message);

    if(!response_stream || http_version.substr(0, 5) != "HTTP/"){
      std::cout << "Invalid response\n";
      return;
    }

    if(status_code != 200){
      std::cout << "Response returned with status code " << status_code << "\n";
      return;
    }else{
        std::cout << "200 response." << std::endl;
    }


    // Read the response headers, which are terminated by a blank line.
    boost::asio::read_until(socket, response, "\r\n\r\n");

    // Process the response headers.
    std::string header;
    while (std::getline(response_stream, header) && header != "\r")
        std::cout << header << "\n";

    if (response.size() > 0)
      std::cout << &response;

    // Write whatever content we already have to output.
    if (response.size() > 0)
      std::cout << &response;

    // Read until EOF, writing data to output as we go.
    while (boost::asio::read(socket, response,
          boost::asio::transfer_at_least(1), error))
      std::cout << &response;
    if (error != boost::asio::error::eof)
      throw boost::system::system_error(error);
}

int main(int argc, char** argv){

    int opt = 0;
    while ((opt = getopt(argc, argv, "n:")) != -1){
        switch (opt){
            case 'n':
                std::strncpy(client_name, optarg, 25);
        }
    }

    register_client();
    
    for(int i = 0; i < paths.size(); i++){
        fs::path dir(paths.at(i));
        directory_recurse(dir);
    }

    post_files();

    return 0;
}