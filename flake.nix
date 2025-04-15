{
  inputs = {
    nixpkgs.url = "github:cachix/devenv-nixpkgs/rolling";
    systems.url = "github:nix-systems/default";
    devenv.url = "github:cachix/devenv";
    devenv.inputs.nixpkgs.follows = "nixpkgs";
  };

  nixConfig = {
    extra-trusted-public-keys =
      "devenv.cachix.org-1:w1cLUi8dv3hnoSPGAuibQv+f9TZLr6cv/Hm9XgU50cw=";
    extra-substituters = "https://devenv.cachix.org";
  };

  outputs = { self, nixpkgs, devenv, systems, ... }@inputs:
    let forEachSystem = nixpkgs.lib.genAttrs (import systems);
    in {
      packages = forEachSystem (system: {
        devenv-up = self.devShells.${system}.default.config.procfileScript;
        devenv-test = self.devShells.${system}.default.config.test;
      });

      devShells = forEachSystem (system:
        let pkgs = nixpkgs.legacyPackages.${system};
        in {
          default = devenv.lib.mkShell {
            inherit inputs pkgs;
            modules = [
              ({ pkgs, config, ... }: {
                # https://devenv.sh/reference/options/
                packages = [ ];

                env.DATABASE_URL = "pgsql://app:app@localhost/app";

                languages.javascript = {
                  enable = true;
                };
                languages.php = {
                  enable = true;
                  package = pkgs.php.buildEnv {
                    extensions = { all, enabled }:
                      with all;
                      enabled ++ [ xdebug redis ];
                    extraConfig = ''
                      memory_limit=256m
                    '';
                  };
                  fpm.pools.web = {
                    settings = {
                      "clear_env" = "no";
                      "pm" = "dynamic";
                      "pm.max_children" = 10;
                      "pm.start_servers" = 2;
                      "pm.min_spare_servers" = 1;
                      "pm.max_spare_servers" = 10;
                    };
                  };
                };
                services.caddy.enable = true;
                services.caddy.virtualHosts.":8000" = {
                  extraConfig = ''
                    root * public
                    php_fastcgi unix/${config.languages.php.fpm.pools.web.socket}
                    file_server
                  '';
                };
                services.postgres = {
                  enable = true;
                  initialDatabases = [{
                    name = "app";
                    user = "app";
                    pass = "app";
                  }];
                };
              })
            ];
          };
        });
    };
}
