<?php

declare(strict_types=1);

use Rector\CodeQuality\Rector\Assign\CombinedAssignRector;
use Rector\CodeQuality\Rector\BooleanAnd\SimplifyEmptyArrayCheckRector;
use Rector\CodeQuality\Rector\BooleanNot\SimplifyDeMorganBinaryRector;
use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachItemsAssignToEmptyArrayToAssignRector;
use Rector\CodeQuality\Rector\Foreach_\ForeachToInArrayRector;
use Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToArrayFilterRector;
use Rector\CodeQuality\Rector\Foreach_\SimplifyForeachToCoalescingRector;
use Rector\CodeQuality\Rector\FuncCall\ArrayKeysAndInArrayToArrayKeyExistsRector;
use Rector\CodeQuality\Rector\FuncCall\ChangeArrayPushToArrayAssignRector;
use Rector\CodeQuality\Rector\FuncCall\InArrayAndArrayKeysToArrayKeyExistsRector;
use Rector\CodeQuality\Rector\FuncCall\IntvalToTypeCastRector;
use Rector\CodeQuality\Rector\FuncCall\RemoveSoleValueSprintfRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyInArrayValuesRector;
use Rector\CodeQuality\Rector\FuncCall\SimplifyRegexPatternRector;
use Rector\CodeQuality\Rector\Identical\BooleanNotIdenticalToNotIdenticalRector;
use Rector\CodeQuality\Rector\Identical\GetClassToInstanceOfRector;
use Rector\CodeQuality\Rector\Identical\SimplifyArraySearchRector;
use Rector\CodeQuality\Rector\Identical\SimplifyBoolIdenticalTrueRector;
use Rector\CodeQuality\Rector\Identical\SimplifyConditionsRector;
use Rector\CodeQuality\Rector\If_\CombineIfRector;
use Rector\CodeQuality\Rector\If_\ConsecutiveNullCompareReturnsToNullCoalesceQueueRector;
use Rector\CodeQuality\Rector\If_\ShortenElseIfRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfElseToTernaryRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfIssetToNullCoalescingRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfNotNullReturnRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfNullableReturnRector;
use Rector\CodeQuality\Rector\If_\SimplifyIfReturnBoolRector;
use Rector\CodeQuality\Rector\LogicalAnd\AndAssignsToSeparateLinesRector;
use Rector\CodeQuality\Rector\LogicalAnd\LogicalToBooleanRector;
use Rector\CodeQuality\Rector\NotEqual\CommonNotEqualRector;
use Rector\CodeQuality\Rector\Return_\SimplifyUselessVariableRector;
use Rector\CodeQuality\Rector\Ternary\ArrayKeyExistsTernaryThenValueToCoalescingRector;
use Rector\CodeQuality\Rector\Ternary\SimplifyDuplicatedTernaryRector;
use Rector\CodeQuality\Rector\Ternary\SimplifyTautologyTernaryRector;
use Rector\CodingStyle\Rector\FuncCall\CountArrayToEmptyArrayComparisonRector;
use Rector\CodingStyle\Rector\Plus\UseIncrementAssignRector;
use Rector\CodingStyle\Rector\Ternary\TernaryConditionVariableAssignmentRector;
use Rector\Core\Configuration\Option;
use Rector\DeadCode\Rector\Array_\RemoveDuplicatedArrayKeyRector;
use Rector\DeadCode\Rector\Assign\RemoveDoubleAssignRector;
use Rector\DeadCode\Rector\Foreach_\RemoveUnusedForeachKeyRector;
use Rector\DeadCode\Rector\FunctionLike\RemoveDuplicatedIfReturnRector;
use Rector\DeadCode\Rector\If_\SimplifyIfElseWithSameContentRector;
use Rector\DeadCode\Rector\Switch_\RemoveDuplicatedCaseInSwitchRector;
use Rector\EarlyReturn\Rector\Foreach_\ChangeNestedForeachIfsToEarlyContinueRector;
use Rector\EarlyReturn\Rector\If_\ChangeIfElseValueAssignToEarlyReturnRector;
use Rector\EarlyReturn\Rector\If_\ChangeNestedIfsToEarlyReturnRector;
use Rector\EarlyReturn\Rector\Return_\PreparedValueToEarlyReturnRector;
use Rector\Php80\Rector\Catch_\RemoveUnusedVariableInCatchRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php80\Rector\Class_\StringableForToStringRector;
use Rector\Php80\Rector\FuncCall\ClassOnObjectRector;
use Rector\Php80\Rector\Identical\StrEndsWithRector;
use Rector\Php80\Rector\Identical\StrStartsWithRector;
use Rector\Php80\Rector\If_\NullsafeOperatorRector;
use Rector\Php80\Rector\NotIdentical\StrContainsRector;
use Rector\Php80\Rector\Switch_\ChangeSwitchToMatchRector;
use Rector\PostRector\Rector\ClassRenamingPostRector;
use Rector\PostRector\Rector\NameImportingPostRector;
use Rector\PostRector\Rector\PropertyAddingPostRector;
use Rector\PostRector\Rector\UseAddingPostRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

// @link https://github.com/rectorphp/rector/blob/main/docs/rector_rules_overview.md
return static function (ContainerConfigurator $containerConfigurator): void {
    // get parameters
    $parameters = $containerConfigurator->parameters();

    // Define what paths we want rector to run on
    $parameters->set(Option::PATHS, [
        //__DIR__ . '/app/Core/**',
    ]);

    // Define what paths we want to skip
    $parameters->set(Option::SKIP, [
        //__DIR__ . '/app/**/Path/**',

        //TODO: buggy on cases mix up default and multiple cases
        ChangeSwitchToMatchRector::class,
    ]);

    $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, getcwd().'/phpstan.neon');
    $parameters->set(Option::AUTO_IMPORT_NAMES, true); // Auto import fully qualified class names
    $parameters->set(Option::IMPORT_SHORT_CLASSES, false); // Skip root namespace classes, like \DateTime or \Exception

    // Get services (needed for register a single rule)
    $services = $containerConfigurator->services();

    //PHP8
    $services->set(NullsafeOperatorRector::class);
    $services->set(RemoveUnusedVariableInCatchRector::class);
    $services->set(StrStartsWithRector::class);
    $services->set(StrEndsWithRector::class);
    $services->set(StrContainsRector::class);
    $services->set(ClassOnObjectRector::class);
    $services->set(ClassPropertyAssignToConstructorPromotionRector::class);
    $services->set(StringableForToStringRector::class);

    // CodeQuality
    $services->set(AndAssignsToSeparateLinesRector::class);
    $services->set(ArrayKeyExistsTernaryThenValueToCoalescingRector::class);
    $services->set(ArrayKeysAndInArrayToArrayKeyExistsRector::class);
    $services->set(BooleanNotIdenticalToNotIdenticalRector::class);
    $services->set(ChangeArrayPushToArrayAssignRector::class);
    $services->set(CombineIfRector::class);
    $services->set(CombinedAssignRector::class);
    $services->set(CommonNotEqualRector::class);
    $services->set(ConsecutiveNullCompareReturnsToNullCoalesceQueueRector::class);
    $services->set(ForeachItemsAssignToEmptyArrayToAssignRector::class);
    $services->set(ForeachToInArrayRector::class);
    $services->set(GetClassToInstanceOfRector::class);
    $services->set(InArrayAndArrayKeysToArrayKeyExistsRector::class);
    $services->set(IntvalToTypeCastRector::class);
    $services->set(JoinStringConcatRector::class);
    $services->set(LogicalToBooleanRector::class);
    $services->set(RemoveSoleValueSprintfRector::class);
    $services->set(ShortenElseIfRector::class);
    $services->set(SimplifyArraySearchRector::class);
    $services->set(SimplifyBoolIdenticalTrueRector::class);
    $services->set(SimplifyConditionsRector::class);
    $services->set(SimplifyDeMorganBinaryRector::class);
    $services->set(SimplifyDuplicatedTernaryRector::class);
    $services->set(SimplifyEmptyArrayCheckRector::class);
    $services->set(SimplifyForeachToArrayFilterRector::class);
    $services->set(SimplifyForeachToCoalescingRector::class);
    $services->set(SimplifyIfElseToTernaryRector::class);
    $services->set(SimplifyIfIssetToNullCoalescingRector::class);
    $services->set(SimplifyIfNotNullReturnRector::class);
    $services->set(SimplifyIfNullableReturnRector::class);
    $services->set(SimplifyIfReturnBoolRector::class);
    $services->set(SimplifyInArrayValuesRector::class);
    $services->set(SimplifyRegexPatternRector::class);
    $services->set(SimplifyTautologyTernaryRector::class);
    $services->set(SimplifyUselessVariableRector::class);

    // CodeStyle
    $services->set(TernaryConditionVariableAssignmentRector::class);
    $services->set(UseIncrementAssignRector::class);
    $services->set(CountArrayToEmptyArrayComparisonRector::class);

    // DeadCode
    $services->set(RemoveDuplicatedArrayKeyRector::class);
    $services->set(RemoveDoubleAssignRector::class);
    $services->set(RemoveUnusedForeachKeyRector::class);
    $services->set(SimplifyIfElseWithSameContentRector::class);
    $services->set(RemoveDuplicatedIfReturnRector::class);
    $services->set(RemoveDuplicatedCaseInSwitchRector::class);

    // TypeDecleration
    $services->set(AddParamTypeDeclarationRector::class);
    $services->set(AddReturnTypeDeclarationRector::class);
    $services->set(ParamTypeFromStrictTypedPropertyRector::class);

    // EarlyReturn
    $services->set(ChangeIfElseValueAssignToEarlyReturnRector::class);
    $services->set(ChangeNestedIfsToEarlyReturnRector::class);
    $services->set(ChangeNestedForeachIfsToEarlyContinueRector::class);
    $services->set(PreparedValueToEarlyReturnRector::class);

    // PostRector
    $services->set(ClassRenamingPostRector::class);
    $services->set(NameImportingPostRector::class);
    $services->set(UseAddingPostRector::class);
    $services->set(PropertyAddingPostRector::class);
};
