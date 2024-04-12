# Internos

## Teste de unidade

O pacote é testado com [PHPUnit](https://phpunit.de/).
Para executar testes:

```shell
./vendor/bin/phpunit
```

## Teste de mutação

Os testes do pacote são verificados com a estrutura de mutação [Infection](https://infection.github.io/) com o
[Plugin de análise estática de infecção](https://github.com/Roave/infection-static-análise-plugin).
Para executá-lo:

```shell
./vendor/bin/roave-infection-static-analysis-plugin
```

## Análise estática

O código é analisado estaticamente com [Psalm](https://psalm.dev/).
Para executar análise estática:

```shell
./vendor/bin/psalm
```

## Estilo de código

Use [Rector](https://github.com/rectorphp/rector) para fazer a base de código seguir algumas regras específicas ou
use a versão mais recente ou qualquer versão específica do PHP:

```shell
./vendor/bin/rector
```

## Dependências

Use o [ComposerRequireChecker](https://github.com/maglnet/ComposerRequireChecker) para detectar
dependências transitivas do [Composer](https://getcomposer.org/).
