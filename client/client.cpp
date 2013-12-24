#include <iostream>
#include <stdio.h>
#include <stdlib.h>

#include <vector>
#include <string>


#include "boost/filesystem.hpp"
namespace fs = boost::filesystem;

std::vector<std::string> extensions;

void directory_recurse(fs::path& dir){
    if(fs::exists(dir) && fs::is_directory(dir)){
        fs::directory_iterator end;
        for(fs::directory_iterator it(dir); it != end; ++it){
            if(fs::is_regular_file(*it)){
                bool valid_ext = extensions.size() == 0;
                for(std::vector<std::string>::iterator ext = extensions.begin(); ext != extensions.end(); ++ext){
                    if((*ext) == (*it).path().extension()){
                        valid_ext = true;
                        break;
                    }
                }
                if(valid_ext)
                    printf("%s\n", (*it).path().string().c_str());
            }else if(fs::is_directory(*it) && !fs::is_symlink(*it)){
                fs::path rdir = (*it).path();
                directory_recurse(rdir);
            }
        }
    }
}

int main(int argc, char** argv){

    int opt = 0;
    while ((opt = getopt(argc, argv, "e:")) != -1){
        switch (opt){
            case 'e':
                extensions.push_back(optarg);
                break;
        }
    }
    
    for(int i = optind; i < argc; i++){
        fs::path dir(argv[i]);
        directory_recurse(dir);
    }

    return 0;
}