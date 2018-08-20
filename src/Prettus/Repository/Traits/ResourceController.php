<?php

namespace Prettus\Repository\Traits;

use Illuminate\Http\Request;
use Exception;

trait ResourceController
{
    protected function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $data = $this->repository->all();

        $response = [
            'message' => 'List results.',
            'data'    => $data->toArray(),
        ];

//        wantsJson() <=> headers = { "Accept":"application/json" }
        if (request()->wantsJson()) {
            return response()->json($response);
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    protected function show($id)
    {
        $data = $this->repository->find($id);

        $response = [
            'message' => 'Show result.',
            'data' => $data,
        ];

        if (request()->wantsJson()) {
            return response()->json($response);
        }

        return response()->json($response, 200, [], JSON_PRETTY_PRINT);
    }

    protected function doStoreWith($request)
    {
        try {
            if (method_exists($request, 'rules')) {
                $request->validated();
            }

            $data = $this->repository->create($request->all());

            $response = [
                'message' => 'Created successful.',
                'data'    => $data->toArray(),
            ];

            if ($request->wantsJson()) {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    protected function store(Request $request)
    {
        $this->doStoreWith($request);
    }

    protected function doUpdateWith($request, $id)
    {
        try {
            if (method_exists($request, 'rules')) {
                $request->validated();
            }

            $data = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Updated successful.',
                'data'    => $data->toArray(),
            ];

            if ($request->wantsJson()) {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (Exception $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }

    protected function update(Request $request)
    {
        $this->doUpdateWith($request);
    }

    protected function destroy($id)
    {
        try {
            $deleted = $this->repository->delete($id);

            $response = [
                'message' => 'Deleted successful.',
                'deleted' => $deleted,
            ];

            if (request()->wantsJson()) {
                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
        } catch (Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessage()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessage())->withInput();
        }
    }
}
